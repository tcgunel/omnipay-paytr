<?php

namespace Omnipay\Paytr\Message;

use JsonException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Models\PaymentInquiryResponseModel;
use Psr\Http\Message\ResponseInterface;

class PaymentInquiryResponse extends AbstractResponse
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

				$this->response = new PaymentInquiryResponseModel($data);

			} catch (JsonException $e) {

				$this->response = new PaymentInquiryResponseModel([
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

	public function getData(): PaymentInquiryResponseModel
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
