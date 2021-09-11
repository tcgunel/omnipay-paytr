<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Models\CompletePurchaseRequestModel;

/**
 * Paytr Complete Purchase Response
 *
 * @property \Omnipay\Paytr\Message\PurchaseRequest $request
 */
class CompletePurchaseResponse extends AbstractResponse
{
	protected $response;

	protected $request;

	public function __construct(RequestInterface $request, $data)
	{
		parent::__construct($request, $data);

		$this->request = $request;

		$this->response = $data;
	}

	public function isSuccessful(): bool
	{
		return $this->getData()->status === Status::SUCCESS && $this->getData()->hash === $this->getData()->confirmation_hash;
	}

	public function getMessage(): string
	{
		return $this->isSuccessful() ? "OK" : $this->getData()->fail_reason;
	}

	public function getCode(): string
	{
		return $this->getData()->fail_code;
	}

	public function getData(): CompletePurchaseRequestModel
	{
		return $this->response;
	}

	public function getRedirectData()
	{
		return null;
	}

	public function getRedirectUrl(): string
	{
		return '';
	}

}
