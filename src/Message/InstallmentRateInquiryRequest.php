<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paytr\Models\InstallmentRateInquiryRequestModel;

class InstallmentRateInquiryRequest extends RemoteAbstractRequest
{
	protected $endpoint = "https://www.paytr.com/odeme/taksit-oranlari";

	/**
	 * @throws InvalidCreditCardException|InvalidRequestException
	 */
	public function getData(): InstallmentRateInquiryRequestModel
	{
		$this->validateAll();

		$data = new InstallmentRateInquiryRequestModel([
			"merchant_id"  => $this->getMerchantId(),
			"request_id"  => $this->getRequestId(),
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

		$this->validate("requestId");
	}

	protected function createResponse($data): InstallmentRateInquiryResponse
	{
		return $this->response = new InstallmentRateInquiryResponse($this, $data);
	}

	/**
	 * @param InstallmentRateInquiryRequestModel $data
	 * @return ResponseInterface|InstallmentRateInquiryResponse
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
