<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Message\DeleteCardRequest;
use Omnipay\Paytr\Message\DeleteCardResponse;
use Omnipay\Paytr\Models\DeleteCardRequestModel;
use Omnipay\Paytr\Models\DeleteCardResponseModel;
use Omnipay\Paytr\Tests\TestCase;

class DeleteCardTest extends TestCase
{
	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws InvalidCreditCardException
	 * @throws \JsonException
	 */
	public function test_delete_card_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/DeleteCardRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new DeleteCardRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$expected = new DeleteCardRequestModel([
			"merchant_id" => "id",
			"ctoken"      => "ctoken",
			"utoken"      => "utoken",
			"paytr_token" => "OnKQFCMcoWvKz2IzJB4H3TWtm5Ew4rxauGg0vErjjdE=",
		]);

		self::assertEquals($expected, $data);
	}

	public function test_delete_card_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/DeleteCardRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new DeleteCardRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_delete_card_response()
	{
		$httpResponse = $this->getMockHttpResponse('DeleteCardResponseSuccess.txt');

		$response = new DeleteCardResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertTrue($response->isSuccessful());

		$this->assertEquals(new DeleteCardResponseModel([
			'status' => Status::SUCCESS,
		]), $data);
	}

	public function test_delete_card_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('DeleteCardResponseApiError.txt');

		$response = new DeleteCardResponse($this->getMockRequest(), $httpResponse);

		$data = $response->getData();

		$this->assertFalse($response->isSuccessful());

		$this->assertEquals(new DeleteCardResponseModel([
			'status'  => Status::ERROR,
			'err_no'  => "009",
			'err_msg' => "some kind of error occurred. care...",
		]), $data);
	}
}
