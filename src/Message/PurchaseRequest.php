<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Paytr\Constants\PaymentType;
use Omnipay\Paytr\Models\PurchaseRequestModel;
use Omnipay\Paytr\Traits\GettersSettersTrait;

class PurchaseRequest extends RemoteAbstractRequest
{
	use GettersSettersTrait;

	protected $endpoint = "https://www.paytr.com/odeme";

	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws \Omnipay\Common\Exception\InvalidCreditCardException
	 */
	public function getData()
	{
		$this->validateAll();

		$data = new PurchaseRequestModel([
			"merchant_id"       => $this->getMerchantId(),
			"user_ip"           => $this->getClientIp() ?? "127.0.0.1",
			"merchant_oid"      => $this->getTransactionId(),
			"email"             => $this->get_card("getEmail") ?? "dummy-email@example.com",
			"payment_amount"    => $this->getAmountInteger(),
			"payment_type"      => $this->getPaymentType() ?? PaymentType::CARD,
			"installment_count" => $this->getInstallment(),

			"currency"  => $this->getCurrency(),
			"test_mode" => $this->getTestMode(),
			"debug_on"  => $this->getTestMode(),
			"non_3d"    => !$this->getSecure(),

			"paytr_token" => "",

			"card_type"    => $this->getCardSubBrand(),
			"cc_owner"     => $this->get_card("getName"),
			"card_number"  => $this->get_card("getNumber"),
			"expiry_month" => $this->get_card("getExpiryMonth"),
			"expiry_year"  => $this->get_card("getExpiryYear"),
			"cvv"          => $this->get_card("getCvv"),

			"merchant_ok_url"   => $this->getReturnUrl(),
			"merchant_fail_url" => $this->getCancelUrl(),

			"user_name"    => $this->get_card("getName") ?? "",
			"user_address" => implode(" ", [$this->get_card("getAddress1") ?? "", $this->get_card("getAddress2") ?? ""]),
			"user_phone"   => implode("", [$this->get_card("getPhoneExtension") ?? "", $this->get_card("getPhone") ?? ""]),

			"user_basket" => $this->getItems(),

			"ctoken"     => $this->getCardReference(),
			"utoken"     => $this->getUserReference(),
			"store_card" => $this->getStoreCard(),

			"ref_id"     => $this->getRefId(),
		]);

		$data->generateToken(...$this->settings);

		return $data;
	}

	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws \Omnipay\Common\Exception\InvalidCreditCardException
	 */
	protected function validateAll(): void
	{
		$this->validateSettings();

		if ($this->getCardReference()){

			$this->validate("userReference");

		}else{

			$this->getCard()->validate();

		}

		$this->validate(
			"amount",
			"transactionId",
			"installment",
			"items",
			"returnUrl",
			"cancelUrl",
		);
	}

	/**
	 * @throws \Omnipay\Paytr\Exceptions\OmnipayPaytrHashValidationException
	 */
	public function sendData($data)
	{
		return $this->response = new PurchaseResponse($this, $data);
	}

	protected function createResponse($data): void
	{
	}
}
