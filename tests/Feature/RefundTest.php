<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Constants\Brand;
use Omnipay\Paytr\Constants\CardType;
use Omnipay\Paytr\Constants\YesNo;
use Omnipay\Paytr\Message\RefundRequest;
use Omnipay\Paytr\Message\RefundResponse;
use Omnipay\Paytr\Models\RefundRequestModel;
use Omnipay\Paytr\Models\RefundResponseModel;
use Omnipay\Paytr\Tests\TestCase;

class RefundTest extends TestCase
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
	public function test_refund_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/RefundRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$expected = new RefundRequestModel([
			"merchant_id"   => "id",
			"merchant_oid"  => "transactionId",
			"return_amount" => "9.99",
			"paytr_token"   => "vcLAJjhDuf7fn5IcKFrx55vuNr82dgFSIXLufQtOLzM=",
		]);

		self::assertEquals($expected, $data);
	}

	public function test_refund_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/RefundRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_refund_response()
	{
		$httpResponse = $this->getMockHttpResponse('RefundResponseSuccess.txt');

		$response = new RefundResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertTrue($response->isSuccessful());

		$this->assertEquals(new RefundResponseModel([
			'status'        => Status::SUCCESS,
			'is_test'       => 1,
			'merchant_oid'  => "transactionId",
			'return_amount' => "9.99",
		]), $data);
	}

	public function test_charge_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('RefundResponseApiError.txt');

		$response = new RefundResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertFalse($response->isSuccessful());

		$this->assertEquals(new RefundResponseModel([
			'status'  => Status::ERROR,
			'err_no'  => "009",
			'err_msg' => "some kind of error occurred. care...",
		]), $data);
	}
}
