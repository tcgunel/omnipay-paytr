<?php

namespace Omnipay\Paytr\Message;

use JsonException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Models\CaptureResponseModel;
use Psr\Http\Message\ResponseInterface;

/**
 * Response of a PayTR capture. Mirrors {@see RefundResponse}.
 */
class CaptureResponse extends AbstractResponse
{
    protected $response;

    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->request = $request;

        $this->response = $data;

        if ($data instanceof ResponseInterface) {

            $body = (string) $data->getBody();

            try {

                $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

                $this->response = new CaptureResponseModel($decoded);

            } catch (JsonException $e) {

                $this->response = new CaptureResponseModel([
                    'status' => Status::ERROR,
                    'err_msg' => $body,
                ]);

            }

        }
    }

    public function isSuccessful(): bool
    {
        return $this->response->status === Status::SUCCESS;
    }

    public function getTransactionReference(): ?string
    {
        return $this->response->reference_no;
    }

    public function getMessage(): ?string
    {
        return $this->response->err_msg ?? $this->response->reason;
    }

    public function getCode()
    {
        return $this->response->err_no;
    }

    public function getData(): CaptureResponseModel
    {
        return $this->response;
    }
}
