<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paytr\Constants\Currency;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Helpers\Helper;
use Omnipay\Paytr\Message\PaymentInquiryRequest;
use Omnipay\Paytr\Message\PaymentInquiryResponse;
use Omnipay\Paytr\Models\PaymentInquiryRequestModel;
use Omnipay\Paytr\Models\PaymentInquiryResponseModel;
use Omnipay\Paytr\Models\RefundResponseModel;
use Omnipay\Paytr\Tests\TestCase;

class PaymentInquiryTest extends TestCase
{
	public function setUp(): void
	{
		parent::setUp();
	}

	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws InvalidCreditCardException
	 * @throws \JsonException
	 */
	public function test_payment_inquiry_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PaymentInquiryRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PaymentInquiryRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$expected = new PaymentInquiryRequestModel([
			"merchant_id"  => "id",
			"merchant_oid" => "transactionId",
			"paytr_token"  => "60n/Ue6d0E2O6MbNiUdLo79i9XibF62J7uuRmRMzuj0=",
		]);

		self::assertEquals($expected, $data);
	}

	public function test_payment_inquiry_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PaymentInquiryRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PaymentInquiryRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_payment_inquiry_response()
	{
		$httpResponse = $this->getMockHttpResponse('PaymentInquiryResponseSuccess.txt');

		$response = new PaymentInquiryResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertTrue($response->isSuccessful());

		$this->assertEquals(new PaymentInquiryResponseModel([
			'status'         => Status::SUCCESS,
			'merchant_oid'   => 'transactionId',
			'payment_amount' => "99.99",
			'payment_total'  => "50.99",
			'currency'       => Currency::EUR,
			'returns'        => [
				new RefundResponseModel([
					"is_test"       => "1",
					"merchant_id"   => "id",
					"merchant_oid"  => "transactionId",
					"return_amount" => "9.99",
				])
			],
		]), $data);
	}

	public function test_charge_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('PaymentInquiryResponseApiError.txt');

		$response = new PaymentInquiryResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertFalse($response->isSuccessful());

		$this->assertEquals(new PaymentInquiryResponseModel([
			'status'  => Status::ERROR,
			'err_no'  => "009",
			'err_msg' => "some kind of error occurred. care...",
		]), $data);
	}
}
