<?php

namespace Omnipay\Paytr\Message;

use JsonException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Models\CardInquiryResponseModel;
use Psr\Http\Message\ResponseInterface;

class CardInquiryResponse extends AbstractResponse
{
	protected $response;

	protected $request;

	public function __construct(RequestInterface $request, $data)
	{
		parent::__construct($request, $data);

		$this->request = $request;

		//$this->response = $data;

		if ($data instanceof ResponseInterface) {

			$body = (string)$data->getBody();

			try {

				$data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

				if (!isset($data["status"])) {

					foreach ($data as $datum) {

						$this->response[] = new CardInquiryResponseModel($datum);

					}

				}else{

					$this->response = new CardInquiryResponseModel($data);

				}

			} catch (JsonException $e) {

				$this->response = new CardInquiryResponseModel([
					"status"  => Status::ERROR,
					"err_msg" => $body,
				]);

			}

		}
	}

	public function isSuccessful(): bool
	{
		return is_array($this->response) && isset($this->response[0]);
	}

	public function getMessage(): ?string
	{
		return !is_array($this->response) ? $this->response->err_msg : null;
	}

	/**
	 * @return array|mixed|CardInquiryResponseModel
	 */
	public function getData()
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
