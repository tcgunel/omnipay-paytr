<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Paytr Enrolment Response
 *
 * @property \Omnipay\Paytr\Message\PurchaseRequest $request
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
	public function isSuccessful(): bool
	{
		return false;
	}

	public function isRedirect(): bool
	{
		return true;
	}

	public function getRedirectUrl()
	{
		/** @var PurchaseRequest $request */
		$request = $this->getRequest();

		return $request->getEndpoint();
	}

	public function getRedirectMethod(): string
	{
		return 'POST';
	}

	/**
	 * @throws \JsonException
	 */
	public function getRedirectData(): array
	{
		return (array)$this->getData();
	}

}
