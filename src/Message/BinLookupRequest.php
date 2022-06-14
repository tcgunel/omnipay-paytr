<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Helper;
use Omnipay\Paytr\Models\BinLookupRequestModel;

class BinLookupRequest extends RemoteAbstractRequest
{
	protected $endpoint = 'https://www.paytr.com/odeme/api/bin-detail';

	protected $transactionDateTime;

	/**
	 * @throws InvalidCreditCardException|InvalidRequestException
	 */
	public function getData(): BinLookupRequestModel
	{
		$this->validateAll();

		$data = new BinLookupRequestModel([
			"merchant_id" => $this->getMerchantId(),
			"bin_number"  => $this->getCard()->getNumber(),
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

		if (!is_null($this->getCard()->getNumber()) && !preg_match('/^\d{8,19}$/', $this->getCard()->getNumber())) {
			throw new InvalidCreditCardException('Card number should have at least 8 to maximum of 19 digits');
		}
	}

	protected function createResponse($data): BinLookupResponse
	{
		return $this->response = new BinLookupResponse($this, $data);
	}

	/**
	 * @param BinLookupRequestModel $data
	 * @return \Omnipay\Common\Message\ResponseInterface|BinLookupResponse
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
