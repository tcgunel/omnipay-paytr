<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Paytr\Models\CardInquiryRequestModel;

class CardInquiryRequest extends RemoteAbstractRequest
{
	protected $endpoint = "https://www.paytr.com/odeme/capi/list";

	/**
	 * @throws InvalidCreditCardException|InvalidRequestException
	 */
	public function getData(): CardInquiryRequestModel
	{
		$this->validateAll();

		$data = new CardInquiryRequestModel([
			"merchant_id" => $this->getMerchantId(),
			"utoken"  => $this->getUserReference(),
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

		$this->validate("userReference");
	}

	protected function createResponse($data): CardInquiryResponse
	{
		return $this->response = new CardInquiryResponse($this, $data);
	}

	/**
	 * @param CardInquiryRequestModel $data
	 * @return \Omnipay\Common\Message\ResponseInterface|CardInquiryResponse
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
