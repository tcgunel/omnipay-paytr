<?php

namespace Omnipay\Paytr\Traits;

trait GettersSettersTrait
{
	public function getMerchantId()
	{
		return $this->getParameter('merchantId');
	}

	public function setMerchantId($value)
	{
		return $this->setParameter('merchantId', $value);
	}

	public function getMerchantKey()
	{
		return $this->getParameter('merchantKey');
	}

	public function setMerchantKey($value)
	{
		return $this->setParameter('merchantKey', $value);
	}

	public function getMerchantSalt()
	{
		return $this->getParameter('merchantSalt');
	}

	public function setMerchantSalt($value)
	{
		return $this->setParameter('merchantSalt', $value);
	}

	public function getClientIp()
	{
		return $this->getParameter('clientIp');
	}

	public function setClientIp($value)
	{
		return $this->setParameter('clientIp', $value);
	}

	public function getEndpoint()
	{
		return $this->endpoint;
	}
}
