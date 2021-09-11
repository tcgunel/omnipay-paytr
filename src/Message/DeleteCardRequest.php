<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paytr\Models\DeleteCardRequestModel;

class DeleteCardRequest extends RemoteAbstractRequest
{
	protected $endpoint = "https://www.paytr.com/odeme/capi/delete";

	/**
	 * @throws InvalidCreditCardException|InvalidRequestException
	 */
	public function getData(): DeleteCardRequestModel
	{
		$this->validateAll();

		$data = new DeleteCardRequestModel([
			"merchant_id" => $this->getMerchantId(),
			"ctoken"      => $this->getCardReference(),
			"utoken"      => $this->getUserReference(),
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

		$this->validate("cardReference", "userReference");
	}

	protected function createResponse($data): DeleteCardResponse
	{
		return $this->response = new DeleteCardResponse($this, $data);
	}

	/**
	 * @param DeleteCardRequestModel $data
	 * @return ResponseInterface|DeleteCardResponse
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
