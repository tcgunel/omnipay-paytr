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
        return 'OK';
    }

    /**
     * The reference_no carried on a pre-authorization Bildirim, needed to later
     * capture or void the hold. Null for an ordinary sale notification.
     */
    public function getTransactionReference(): ?string
    {
        return $this->getData()->reference_no;
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
