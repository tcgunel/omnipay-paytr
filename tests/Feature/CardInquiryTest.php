<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paytr\Constants\CardSubBrand;
use Omnipay\Paytr\Constants\CardType;
use Omnipay\Paytr\Constants\Currency;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Helpers\Helper;
use Omnipay\Paytr\Message\CardInquiryRequest;
use Omnipay\Paytr\Message\CardInquiryResponse;
use Omnipay\Paytr\Models\CardInquiryRequestModel;
use Omnipay\Paytr\Models\CardInquiryResponseModel;
use Omnipay\Paytr\Models\RefundResponseModel;
use Omnipay\Paytr\Tests\TestCase;

class CardInquiryTest extends TestCase
{
	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws InvalidCreditCardException
	 * @throws \JsonException
	 */
	public function test_card_inquiry_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/CardInquiryRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new CardInquiryRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$expected = new CardInquiryRequestModel([
			"merchant_id" => "id",
			"utoken"      => "userReferenceId",
			"paytr_token" => "GmEhExWRAFg2vrkxkbd1GWSs8mHbJ18Idh7GB6br2dE=",
		]);

		self::assertEquals($expected, $data);
	}

	public function test_card_inquiry_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/CardInquiryRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new CardInquiryRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_card_inquiry_response()
	{
		$httpResponse = $this->getMockHttpResponse('CardInquiryResponseSuccess.txt');

		$response = new CardInquiryResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertTrue($response->isSuccessful());

		$this->assertEquals([
			new CardInquiryResponseModel([
				"ctoken"       => "cardReference",
				"last_4"       => 4358,
				"month"        => 12,
				"year"         => 24,
				"c_bank"       => "Akbank",
				"require_cvv"  => 0,
				"c_name"       => "Example User",
				"c_brand"      => CardSubBrand::AXESS,
				"c_type"       => CardType::CREDIT,
				"businessCard" => null,
				"initial"      => 4,
				"schema"       => "VISA",
				"status"       => null,
				"err_msg"      => null,
			])
		], $data);
	}

	public function test_card_inquiry_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('CardInquiryResponseApiError.txt');

		$response = new CardInquiryResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertFalse($response->isSuccessful());

		$this->assertEquals(new CardInquiryResponseModel([
			'status'  => Status::ERROR,
			'err_msg' => "some kind of error occurred. care...",
		]), $data);
	}
}
