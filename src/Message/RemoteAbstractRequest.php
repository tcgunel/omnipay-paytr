<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Paytr\Traits\GettersSettersTrait;

abstract class RemoteAbstractRequest extends AbstractRequest
{
	use GettersSettersTrait;

	public $settings = [];

	/**
	 * @throws InvalidRequestException
	 */
	protected function validateSettings(): void
	{
		$this->validate("merchantId", "merchantKey", "merchantSalt");

		$this->settings = [$this->getMerchantSalt(), $this->getMerchantKey(), $this->getMerchantId()];
	}

	protected function get_card($key)
	{
		return $this->getCard() ? $this->getCard()->$key() : null;
	}

	abstract protected function createResponse($data);
}
