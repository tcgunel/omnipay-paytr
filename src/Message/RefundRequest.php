<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paytr\Models\RefundRequestModel;

class RefundRequest extends RemoteAbstractRequest
{
	protected $endpoint = 'https://www.paytr.com/odeme/iade';

	/**
	 * @throws InvalidCreditCardException|InvalidRequestException
	 */
	public function getData(): RefundRequestModel
	{
		$this->validateAll();

		$data = new RefundRequestModel([
			"merchant_id"   => $this->getMerchantId(),
			"merchant_oid"  => $this->getTransactionId(),
			"return_amount" => $this->getAmount(),
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

		$this->validate("transactionId", "amount");
	}

	protected function createResponse($data): RefundResponse
	{
		return $this->response = new RefundResponse($this, $data);
	}

	/**
	 * @param RefundRequestModel $data
	 * @return ResponseInterface|RefundResponse
	 */
	public function sendData($data)
	{
		$httpResponse = $this->httpClient->request(
			'POST',
			$this->getEndpoint(),
			[
				'Content-Type' => 'application/x-www-form-urlencoded',
				'Accept'       => 'application/json',
			],
			http_build_query($data, null, '&')
		);

		return $this->createResponse($httpResponse);
	}
}
