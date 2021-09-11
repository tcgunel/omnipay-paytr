<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paytr\Constants\Currency;
use Omnipay\Paytr\Constants\PaymentType;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Message\CompletePurchaseRequest;
use Omnipay\Paytr\Message\CompletePurchaseResponse;
use Omnipay\Paytr\Models\CompletePurchaseRequestModel;
use Omnipay\Paytr\Tests\TestCase;

class CompletePurchaseTest extends TestCase
{
	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws InvalidCreditCardException
	 * @throws \JsonException
	 */
	public function test_complete_purchase_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/CompletePurchaseRequest.json");

		$post = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->setMerchantId($post["merchantId"]);
		$request->setMerchantKey($post["merchantKey"]);
		$request->setMerchantSalt($post["merchantSalt"]);

		$request->setToken($post["hash"]);
		$request->setTransactionId($post["merchant_oid"]);
		$request->setStatus($post["status"]);
		$request->setAmountInteger($post["payment_amount"]);
		$request->setTotalAmount($post["total_amount"]);
		$request->setTestMode($post["test_mode"]);
		$request->setPaymentType($post["payment_type"]);
		$request->setCurrency($post["currency"]);
		$request->setInstallment($post["installment_count"]);
		$request->setFailCode($post["failed_reason_code"]);
		$request->setFailReason($post["failed_reason_msg"]);

		$data = $request->getData();

		$expected = new CompletePurchaseRequestModel([
			"merchant_id"       => "id",
			"merchant_oid"      => "transactionId",
			"status"            => "success",
			"total_amount"      => "1234",
			"payment_amount"    => "1234",
			"hash"              => "LqkSMvTmozAD+zLTj21CvP20rnJxitym5tb1sypQJZ4=",
			"test_mode"         => "1",
			"payment_type"      => PaymentType::CARD,
			"currency"          => Currency::TL,
			"installment_count" => "1",
			"utoken"            => null,
			"fail_code"         => "",
			"fail_reason"       => "",
			"confirmation_hash" => "LqkSMvTmozAD+zLTj21CvP20rnJxitym5tb1sypQJZ4=",
		]);

		self::assertEquals($expected, $data);
	}

	public function test_complete_purchase_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/CompletePurchaseRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_complete_purchase_response()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/CompletePurchaseResponseSuccess.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$expected = new CompletePurchaseRequestModel($options);

		$expected->generateToken($options["merchantSalt"], $options["merchantKey"], $options["merchantId"]);

		$response = new CompletePurchaseResponse($this->getMockRequest(), $expected);

		$this->assertTrue($response->isSuccessful());

		$this->assertSame("OK", $response->getMessage());
	}

	public function test_complete_purchase_response_api_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/CompletePurchaseResponseApiError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$expected = new CompletePurchaseRequestModel($options);

		$response = new CompletePurchaseResponse($this->getMockRequest(), $expected);

		$this->assertFalse($response->isSuccessful());

		$this->assertSame($expected->status, Status::ERROR);
	}
}
