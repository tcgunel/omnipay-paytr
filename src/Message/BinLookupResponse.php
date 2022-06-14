<?php

namespace Omnipay\Paytr\Message;

use JsonException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Exceptions\OmnipayPaytrBinLookupException;
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

                if ($this->response->status === 'failed') {

                    throw new OmnipayPaytrBinLookupException('Bin lookup failed.');

                }

            } catch (JsonException $e) {

				$this->response = new BinLookupResponseModel([
					"status"  => Status::ERROR,
					"err_msg" => $body,
				]);

            } catch (OmnipayPaytrBinLookupException $e) {

                $this->response = new BinLookupResponseModel([
                    "status"  => Status::ERROR,
                    "err_msg" => $e->getMessage(),
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
