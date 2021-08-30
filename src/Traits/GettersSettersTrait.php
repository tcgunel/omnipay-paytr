<?php

namespace Omnipay\Paytr\Traits;

trait GettersSettersTrait
{
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
