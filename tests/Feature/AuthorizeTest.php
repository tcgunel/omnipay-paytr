<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paytr\Message\AuthorizeRequest;
use Omnipay\Paytr\Message\AuthorizeResponse;
use Omnipay\Paytr\Tests\TestCase;

class AuthorizeTest extends TestCase
{
    private function baseOptions(): array
    {
        return [
            'merchantSalt' => 'salt',
            'merchantKey' => 'key',
            'merchantId' => 'id',

            'clientIp' => '85.34.78.112',
            'transactionId' => 'TESTPREAUTH1',
            'amount' => '14.55',
            'currency' => 'TRY',
            'installment' => 1,
            'testMode' => 1,
            'clientLang' => 'tr',

            'card' => [
                'firstName' => 'Example',
                'lastName' => 'User',
                'number' => '9792030394440796',
                'expiryMonth' => '12',
                'expiryYear' => '2099',
                'cvv' => '000',
            ],
            'items' => [
                ['name' => 'p1', 'description' => 'p1', 'quantity' => 1, 'price' => 14.55],
            ],
        ];
    }

    /**
     * Non-3D pre-authorization: auth_type=preauth, non_3d=1, and the token hash
     * is byte-identical to the documented PayTR preauth formula:
     *   merchant_id . user_ip . merchant_oid . email . payment_amount .
     *   payment_type . installment_count . currency . test_mode . non_3d . salt
     */
    public function test_authorize_non_3d_request()
    {
        $options = $this->baseOptions();
        $options['secure'] = false;

        $request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize($options);

        $data = $request->getData();

        self::assertSame('preauth', $data->auth_type);
        self::assertSame(1, $data->non_3d);          // non-secure => non_3d = 1 (int, not bool)
        self::assertSame(0, $data->installment_count); // 1 is normalised to 0
        self::assertSame('TL', $data->currency);       // TRY rewritten
        self::assertSame(14.55, $data->payment_amount);
        self::assertSame('https://www.paytr.com/odeme/auth', $request->getEndpoint());

        // Independently computed golden token (mirrors preauth.php).
        $hashString = 'id' . '85.34.78.112' . 'TESTPREAUTH1' . 'dummy-email@example.com'
            . '14.55' . 'card' . '0' . 'TL' . '1' . '1' . 'salt';
        $expected = base64_encode(hash_hmac('sha256', $hashString, 'key', true));

        self::assertSame($expected, $data->paytr_token);
    }

    /**
     * 3D pre-authorization: non_3d=0 and the browser return URLs are required.
     */
    public function test_authorize_3d_request()
    {
        $options = $this->baseOptions();
        $options['secure'] = true;
        $options['returnUrl'] = 'https://shop.example/ok';
        $options['cancelUrl'] = 'https://shop.example/fail';

        $request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize($options);

        $data = $request->getData();

        self::assertSame(0, $data->non_3d);
        self::assertSame('https://shop.example/ok', $data->merchant_ok_url);
        self::assertSame('https://shop.example/fail', $data->merchant_fail_url);

        $hashString = 'id' . '85.34.78.112' . 'TESTPREAUTH1' . 'dummy-email@example.com'
            . '14.55' . 'card' . '0' . 'TL' . '1' . '0' . 'salt';
        $expected = base64_encode(hash_hmac('sha256', $hashString, 'key', true));

        self::assertSame($expected, $data->paytr_token);
    }

    /**
     * Regression: the backend passes testMode as a native bool. A bool false must
     * still hash and post as "0" (not "" in the hash but "0" on the wire), or every
     * live (non-test) pre-authorization token would be rejected by PayTR.
     */
    public function test_authorize_bool_testmode_is_hash_consistent()
    {
        $options = $this->baseOptions();
        $options['secure'] = false;
        $options['testMode'] = false; // native bool, as the backend sets it

        $request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize($options);

        $data = $request->getData();

        self::assertSame(0, $data->test_mode); // real int, not bool false

        // The value hashed must equal the value posted on the wire.
        self::assertStringContainsString('test_mode=0', http_build_query($data, '', '&'));

        $hashString = 'id' . '85.34.78.112' . 'TESTPREAUTH1' . 'dummy-email@example.com'
            . '14.55' . 'card' . '0' . 'TL' . '0' . '1' . 'salt'; // test_mode=0, non_3d=1
        $expected = base64_encode(hash_hmac('sha256', $hashString, 'key', true));

        self::assertSame($expected, $data->paytr_token);
    }

    public function test_authorize_3d_requires_return_urls()
    {
        $options = $this->baseOptions();
        $options['secure'] = true; // no returnUrl / cancelUrl

        $request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_authorize_rejects_installments()
    {
        $options = $this->baseOptions();
        $options['secure'] = false;
        $options['installment'] = 3;

        $request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_authorize_response_non_3d_success()
    {
        $httpResponse = $this->getMockHttpResponse('AuthorizeResponseNon3dSuccess.txt');

        $response = new AuthorizeResponse($this->getMockRequest(), $httpResponse);

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertNull($response->getHtmlContent());
        self::assertNull($response->getTransactionReference());
    }

    public function test_authorize_response_3d_redirect()
    {
        $httpResponse = $this->getMockHttpResponse('AuthorizeResponse3dRedirect.txt');

        $response = new AuthorizeResponse($this->getMockRequest(), $httpResponse);

        self::assertFalse($response->isSuccessful()); // challenge issued, not settled
        self::assertTrue($response->isRedirect());
        self::assertSame(base64_decode('PGgxPjNEPC9oMT4='), $response->getHtmlContent());
    }

    public function test_authorize_response_error()
    {
        $httpResponse = $this->getMockHttpResponse('AuthorizeResponseError.txt');

        $response = new AuthorizeResponse($this->getMockRequest(), $httpResponse);

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('Gecersiz istek', $response->getMessage());
        self::assertSame('104', $response->getCode());
    }
}
