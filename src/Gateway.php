<?php

namespace Omnipay\Paytr;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Paytr\Constants\PaymentType;
use Omnipay\Paytr\Message\CardInquiryRequest;
use Omnipay\Paytr\Message\CompletePurchaseRequest;
use Omnipay\Paytr\Message\DeleteCardRequest;
use Omnipay\Paytr\Message\InstallmentRateInquiryRequest;
use Omnipay\Paytr\Message\PaymentInquiryRequest;
use Omnipay\Paytr\Message\PurchaseRequest;
use Omnipay\Paytr\Message\RefundRequest;
use Omnipay\Paytr\Traits\GettersSettersTrait;
use Omnipay\Paytr\Message\BinLookupRequest;

/**
 * Paytr Gateway
 * (c) Tolga Can Günel
 * 2015, mobius.studio
 * http://www.github.com/tcgunel/omnipay-paytr
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = [])
 */
class Gateway extends AbstractGateway
{
	use GettersSettersTrait;

	public function getName(): string
	{
		return 'Paytr';
	}

	public function getDefaultParameters()
	{
		return [
			"clientIp" => "127.0.0.1",

			"merchantId"   => "",
			"merchantKey"  => "",
			"merchantSalt" => "",

			"ref_id" => "",

			"installment" => "1",

			"paymentType" => [PaymentType::CARD, PaymentType::CARD_POINTS],
		];
	}

	public function binLookup(array $options = array()): AbstractRequest
	{
		return $this->createRequest(BinLookupRequest::class, $options);
	}

	public function paymentInquiry(array $options = array()): AbstractRequest
	{
		return $this->createRequest(PaymentInquiryRequest::class, $options);
	}

	public function refund(array $options = array()): AbstractRequest
	{
		return $this->createRequest(RefundRequest::class, $options);
	}

	public function purchase(array $options = [])
	{
		return $this->createRequest(PurchaseRequest::class, $options);
	}

	public function completePurchase(array $options = [])
	{
		return $this->createRequest(CompletePurchaseRequest::class, $options);
	}

	public function listCard(array $options = array()): AbstractRequest
	{
		return $this->createRequest(CardInquiryRequest::class, $options);
	}

	public function deleteCard(array $options = array()): AbstractRequest
	{
		return $this->createRequest(DeleteCardRequest::class, $options);
	}

	public function installmentRates(array $options = array()): AbstractRequest
	{
		return $this->createRequest(InstallmentRateInquiryRequest::class, $options);
	}
}
