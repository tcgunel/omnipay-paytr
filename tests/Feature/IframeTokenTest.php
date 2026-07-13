<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paytr\Message\IframeTokenRequest;
use Omnipay\Paytr\Message\IframeTokenResponse;
use Omnipay\Paytr\Models\IframeTokenRequestModel;
use Omnipay\Paytr\Tests\TestCase;

class IframeTokenTest extends TestCase
{
    /**
     * @throws InvalidRequestException|\JsonException
     */
    public function test_iframe_token_request()
    {
        $options = json_decode(
            file_get_contents(__DIR__ . '/../Mock/IframeTokenRequest.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $request = new IframeTokenRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        /** @var IframeTokenRequestModel $data */
        $data = $request->getData();

        self::assertInstanceOf(IframeTokenRequestModel::class, $data);
        self::assertSame('https://www.paytr.com/odeme/api/get-token', $request->getEndpoint());

        // payment_amount MUST be the integer kuruş (12.34 => 1234), never the
        // ÷100 decimal the direct-API formatter would produce.
        self::assertSame(1234, $data->payment_amount);

        // user_basket MUST be base64(json), NOT the htmlentities encoding the
        // direct API uses.
        $expectedBasket = base64_encode(json_encode(
            [['Perspiciatis et facilis tempore facilis.', 12.34, 1]],
            JSON_THROW_ON_ERROR
        ));
        self::assertSame($expectedBasket, $data->user_basket);

        self::assertSame('TL', $data->currency);
        self::assertSame('dummy-email@example.com', $data->email);
        self::assertSame(0, $data->no_installment);
        self::assertSame(0, $data->max_installment);

        // paytr_token follows the documented iframe formula exactly:
        // base64(hmac_sha256(hash_str . merchant_salt, merchant_key)).
        $hashStr =
            $data->merchant_id .
            $data->user_ip .
            $data->merchant_oid .
            $data->email .
            $data->payment_amount .
            $data->user_basket .
            $data->no_installment .
            $data->max_installment .
            $data->currency .
            $data->test_mode;

        $expectedToken = base64_encode(hash_hmac('sha256', $hashStr . 'salt', 'key', true));

        self::assertSame($expectedToken, $data->paytr_token);
    }

    public function test_iframe_token_request_validation_error()
    {
        $request = new IframeTokenRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize([
            'merchantId' => 'id',
            'merchantKey' => 'key',
            'merchantSalt' => 'salt',
        ]);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_iframe_token_response_success_is_a_redirect()
    {
        $httpResponse = $this->getMockHttpResponse('IframeTokenResponseSuccess.txt');

        $response = new IframeTokenResponse($this->getMockRequest(), $httpResponse);

        self::assertFalse($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertSame('28cc613c3d7633cfa4ed0956fdf901e05cf9d9cc0c2ef8db54fa', $response->getToken());
        self::assertSame(
            'https://www.paytr.com/odeme/guvenli/28cc613c3d7633cfa4ed0956fdf901e05cf9d9cc0c2ef8db54fa',
            $response->getRedirectUrl()
        );
        self::assertSame('GET', $response->getRedirectMethod());
    }

    public function test_iframe_token_response_failed_is_not_a_redirect()
    {
        $httpResponse = $this->getMockHttpResponse('IframeTokenResponseFailed.txt');

        $response = new IframeTokenResponse($this->getMockRequest(), $httpResponse);

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('', $response->getRedirectUrl());
        self::assertSame('Zorunlu alan degeri gecersiz: merchant_id', $response->getMessage());
    }
}
