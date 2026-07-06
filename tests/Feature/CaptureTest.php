<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paytr\Message\CaptureRequest;
use Omnipay\Paytr\Message\CaptureResponse;
use Omnipay\Paytr\Tests\TestCase;

class CaptureTest extends TestCase
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
            'amount' => '14.55',
        ];
    }

    /**
     * Capture token hash (capture.php):
     *   merchant_id . client_ip . merchant_oid . reference_no . payment_amount . salt
     * payment_amount is passed as kuruş (getAmountInteger) and ÷100 by the model.
     */
    public function test_capture_request()
    {
        $request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize($this->baseOptions());

        $data = $request->getData();

        self::assertSame('capture', $data->auth_type);
        self::assertSame('TESTPREAUTH1', $data->merchant_oid);
        self::assertSame('A1234567890123456', $data->reference_no);
        self::assertSame(14.55, $data->payment_amount);
        self::assertSame('85.34.78.112', $data->client_ip);
        self::assertSame('https://www.paytr.com/odeme/auth', $request->getEndpoint());

        $hashString = 'id' . '85.34.78.112' . 'TESTPREAUTH1' . 'A1234567890123456' . '14.55' . 'salt';
        $expected = base64_encode(hash_hmac('sha256', $hashString, 'key', true));

        self::assertSame($expected, $data->paytr_token);
    }

    public function test_capture_validation_error_missing_reference()
    {
        $options = $this->baseOptions();
        unset($options['transactionReference']);

        $request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_capture_response_success()
    {
        $httpResponse = $this->getMockHttpResponse('CaptureResponseSuccess.txt');

        $response = new CaptureResponse($this->getMockRequest(), $httpResponse);

        self::assertTrue($response->isSuccessful());
        self::assertSame('A1234567890123456', $response->getTransactionReference());
    }

    public function test_capture_response_error()
    {
        $httpResponse = $this->getMockHttpResponse('CaptureResponseError.txt');

        $response = new CaptureResponse($this->getMockRequest(), $httpResponse);

        self::assertFalse($response->isSuccessful());
        self::assertSame('capture failed', $response->getMessage());
        self::assertSame('09', $response->getCode());
    }
}
