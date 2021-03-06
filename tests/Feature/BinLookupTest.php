<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Constants\CardSubBrand;
use Omnipay\Paytr\Constants\CardType;
use Omnipay\Paytr\Constants\YesNo;
use Omnipay\Paytr\Message\BinLookupRequest;
use Omnipay\Paytr\Message\BinLookupResponse;
use Omnipay\Paytr\Models\BinLookupRequestModel;
use Omnipay\Paytr\Models\BinLookupResponseModel;
use Omnipay\Paytr\Tests\TestCase;

class BinLookupTest extends TestCase
{
	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws InvalidCreditCardException
	 * @throws \JsonException
	 */
	public function test_bin_lookup_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/BinLookupRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new BinLookupRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$expected = new BinLookupRequestModel([
			'merchant_id' => "id",
			'bin_number'  => '545616',
			'paytr_token' => "eym7GF3gnQD1078YNpc6xF5DBega64CQ4/bwcuWIUK4=",
		]);

		self::assertEquals($expected, $data);
	}

	public function test_bin_lookup_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/BinLookupRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new BinLookupRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidCreditCardException::class);

		$request->getData();
	}

	public function test_bin_lookup_response()
	{
		$httpResponse = $this->getMockHttpResponse('BinLookupResponseSuccess.txt');

		$response = new BinLookupResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertTrue($response->isSuccessful());

		$this->assertEquals(new BinLookupResponseModel([
			'status'       => Status::SUCCESS,
			'cardType'     => CardType::CREDIT,
			'businessCard' => YesNo::YES,
			'bank'         => "QNB Finansbank",
			'brand'        => CardSubBrand::WORLD,
			"schema"       => "MASTERCARD",
			"bankCode"     => 111,
			"allow_non3d"  => false
		]), $data);
	}

	public function test_bin_lookup_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('BinLookupResponseApiError.txt');

		$response = new BinLookupResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertFalse($response->isSuccessful());

		$this->assertEquals(new BinLookupResponseModel([
			'status'  => Status::ERROR,
			'err_msg' => "some kind of error occurred. care...",
		]), $data);
	}
}
