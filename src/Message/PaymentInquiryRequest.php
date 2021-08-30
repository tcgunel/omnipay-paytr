<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paytr\Models\PaymentInquiryRequestModel;

class PaymentInquiryRequest extends RemoteAbstractRequest
{
	protected $endpoint = "https://www.paytr.com/odeme/durum-sorgu";

	/**
	 * @throws InvalidCreditCardException|InvalidRequestException
	 */
	public function getData(): PaymentInquiryRequestModel
	{
		$this->validateAll();

		$data = new PaymentInquiryRequestModel([
			"merchant_id"  => $this->getMerchantId(),
			"merchant_oid" => $this->getTransactionId(),
		]);

		$data->generateToken(...$this->settings);

		return $data;
	}

	/**
	 * @throws InvalidCreditCardException|InvalidRequestException
	 */
	protected function validateAll(): void
	{
		$this->validateSettings();

		$this->validate("transactionId");
	}

	protected function createResponse($data): PaymentInquiryResponse
	{
		return $this->response = new PaymentInquiryResponse($this, $data);
	}

	/**
	 * @param PaymentInquiryRequestModel $data
	 * @return ResponseInterface|PaymentInquiryResponse
	 */
	public function sendData($data)
	{
		$httpResponse = $this->httpClient->request(
			'POST',
			$this->getEndpoint(),
			[
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
			],
			json_encode($data)
		);

		return $this->createResponse($httpResponse);
	}
}
