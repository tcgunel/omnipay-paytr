<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Message\InstallmentRateInquiryRequest;
use Omnipay\Paytr\Message\InstallmentRateInquiryResponse;
use Omnipay\Paytr\Models\InstallmentRateInquiryRequestModel;
use Omnipay\Paytr\Models\InstallmentRateInquiryResponseModel;
use Omnipay\Paytr\Tests\TestCase;

class InstallmentRateInquiryTest extends TestCase
{
	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws InvalidCreditCardException
	 * @throws \JsonException
	 */
	public function test_installment_rate_inquiry_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/InstallmentRateInquiryRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new InstallmentRateInquiryRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$expected = new InstallmentRateInquiryRequestModel([
			"merchant_id" => "id",
			"request_id"  => "requestId",
			"paytr_token" => "NP4Mo528k+SOOYzoN0HQ9D/q1aHQhZuEF6eOhn8RLIQ=",
		]);

		self::assertEquals($expected, $data);
	}

	public function test_installment_rate_inquiry_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/InstallmentRateInquiryRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new InstallmentRateInquiryRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_installment_rate_inquiry_response()
	{
		$httpResponse = $this->getMockHttpResponse('InstallmentRateInquiryResponseSuccess.txt');

		$response = new InstallmentRateInquiryResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertTrue($response->isSuccessful());

		$this->assertEquals(new InstallmentRateInquiryResponseModel([
			'status'           => Status::SUCCESS,
			'request_id'       => "requestId",
			'max_inst_non_bus' => 12,
			'err_msg'          => null,
			'oranlar'          => [
				"world" => [
					"taksit_2"  => 3.92,
					"taksit_3"  => 4.91,
					"taksit_4"  => 5.9,
					"taksit_5"  => 6.88,
					"taksit_6"  => 7.87,
					"taksit_7"  => 8.86,
					"taksit_8"  => 9.84,
					"taksit_9"  => 10.83,
					"taksit_10" => 11.82,
					"taksit_11" => 12.8,
					"taksit_12" => 13.79,
				]
			],
		]), $data);
	}

	public function test_installment_rate_inquiry_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('InstallmentRateInquiryResponseApiError.txt');

		$response = new InstallmentRateInquiryResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertFalse($response->isSuccessful());

		$this->assertEquals(new InstallmentRateInquiryResponseModel([
			'status'  => Status::ERROR,
			'err_msg' => "some kind of error occurred. care...",
		]), $data);
	}
}
