<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paytr\Message\VoidRequest;
use Omnipay\Paytr\Message\VoidResponse;
use Omnipay\Paytr\Tests\TestCase;

class VoidTest extends TestCase
{
    private function baseOptions(): array
    {
        return [
            'merchantSalt' => 'salt',
            'merchantKey' => 'key',
            'merchantId' => 'id',

            'clientIp' => '85.34.78.112',
            'transactionId' => 'TESTPREAUTH1',            // merchant_oid
            'transactionReference' => 'A1234567890123456', // reference_no from the Bildirim
        ];
    }

    /**
     * Void token hash (cancel.php):
     *   merchant_id . client_ip . merchant_oid . reference_no . salt   (no amount)
     */
    public function test_void_request()
    {
        $request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize($this->baseOptions());

        $data = $request->getData();

        self::assertSame('cancel', $data->auth_type);
        self::assertSame('TESTPREAUTH1', $data->merchant_oid);
        self::assertSame('A1234567890123456', $data->reference_no);
        self::assertSame('85.34.78.112', $data->client_ip);
        self::assertSame('https://www.paytr.com/odeme/auth', $request->getEndpoint());

        $hashString = 'id' . '85.34.78.112' . 'TESTPREAUTH1' . 'A1234567890123456' . 'salt';
        $expected = base64_encode(hash_hmac('sha256', $hashString, 'key', true));

        self::assertSame($expected, $data->paytr_token);
    }

    public function test_void_validation_error_missing_reference()
    {
        $options = $this->baseOptions();
        unset($options['transactionReference']);

        $request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_void_response_success()
    {
        $httpResponse = $this->getMockHttpResponse('VoidResponseSuccess.txt');

        $response = new VoidResponse($this->getMockRequest(), $httpResponse);

        self::assertTrue($response->isSuccessful());
        self::assertSame('A1234567890123456', $response->getTransactionReference());
    }

    public function test_void_response_error()
    {
        $httpResponse = $this->getMockHttpResponse('VoidResponseError.txt');

        $response = new VoidResponse($this->getMockRequest(), $httpResponse);

        self::assertFalse($response->isSuccessful());
        self::assertSame('cancel failed', $response->getMessage());
        self::assertSame('09', $response->getCode());
    }
}
