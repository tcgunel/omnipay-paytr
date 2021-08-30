<?php

namespace Omnipay\Paytr;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Paytr\Message\PaymentInquiryRequest;
use Omnipay\Paytr\Traits\GettersSettersTrait;
use Omnipay\Paytr\Message\BinLookupRequest;

/**
 * Paytr Gateway
 * (c) Tolga Can Günel
 * 2015, mobius.studio
 * http://www.github.com/tcgunel/omnipay-ipara
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
		];
	}

	public function binLookup(array $parameters = array()): AbstractRequest
	{
		return $this->createRequest(BinLookupRequest::class, $parameters);
	}

	public function paymentInquiry(array $parameters = array()): AbstractRequest
	{
		return $this->createRequest(PaymentInquiryRequest::class, $parameters);
	}
}
