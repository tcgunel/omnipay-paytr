<?php

namespace Omnipay\Paytr\Message;

use JsonException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paytr\Constants\BinStatus;
use Omnipay\Paytr\Models\BinLookupResponseModel;
use Psr\Http\Message\ResponseInterface;

class BinLookupResponse extends AbstractResponse
{
	protected $response;

	protected $request;

	public function __construct(RequestInterface $request, $data)
	{
		parent::__construct($request, $data);

		$this->request = $request;

		$this->response = $data;

		if ($data instanceof ResponseInterface) {

			$body = (string)$data->getBody();

			try {

				$data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

				$this->response = new BinLookupResponseModel($data);

			} catch (JsonException $e) {

				$this->response = new BinLookupResponseModel([
					"status"  => BinStatus::ERROR,
					"err_msg" => $body,
				]);

			}

		}
	}

	public function isSuccessful(): bool
	{
		return $this->response->status === BinStatus::SUCCESS;
	}

	public function getMessage(): string
	{
		return $this->response->err_msg;
	}

	public function getData(): BinLookupResponseModel
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
