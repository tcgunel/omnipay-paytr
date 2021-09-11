<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Paytr\Models\CompletePurchaseRequestModel;
use Omnipay\Paytr\Traits\GettersSettersTrait;

class CompletePurchaseRequest extends RemoteAbstractRequest
{
	use GettersSettersTrait;

	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 */
	public function getData()
	{
		$this->validateAll();

		$data = new CompletePurchaseRequestModel([
			"merchant_id"       => $this->getMerchantId(),
			"merchant_oid"      => $this->getTransactionId(),
			"status"            => $this->getStatus(),
			"total_amount"      => $this->getTotalAmount(),
			"payment_amount"    => $this->getAmountInteger(),
			"hash"              => $this->getToken(),
			"test_mode"         => $this->getTestMode(),
			"payment_type"      => $this->getPaymentType(),
			"currency"          => $this->getCurrency(),
			"installment_count" => $this->getInstallment(),
			"fail_code"         => $this->getFailCode(),
			"fail_reason"       => $this->getFailReason(),
		]);

		$data->generateToken(...$this->settings);

		return $data;
	}

	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 */
	protected function validateAll(): void
	{
		$this->validateSettings();

		$this->validate("amount", "transactionId", "status");
	}

	/**
	 * @param CompletePurchaseRequestModel $data
	 * @return CompletePurchaseResponse
	 */
	public function sendData($data)
	{
		return $this->response = new CompletePurchaseResponse($this, $data);
	}

	protected function createResponse($data): void
	{
	}
}