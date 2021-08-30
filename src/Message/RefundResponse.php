<?php

namespace Omnipay\Paytr\Message;

use JsonException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Models\RefundResponseModel;
use Psr\Http\Message\ResponseInterface;

class RefundResponse extends AbstractResponse
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

				$this->response = new RefundResponseModel($data);

			} catch (JsonException $e) {

				$this->response = new RefundResponseModel([
					"status"  => Status::ERROR,
					"err_msg" => $body,
				]);

			}

		}
	}

	public function isSuccessful(): bool
	{
		return $this->response->status === Status::SUCCESS;
	}

	public function getMessage(): string
	{
		return $this->response->err_msg;
	}

	public function getData(): RefundResponseModel
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
