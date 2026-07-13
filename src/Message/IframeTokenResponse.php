<?php

namespace Omnipay\Paytr\Message;

use JsonException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Models\IframeTokenResponseModel;
use Psr\Http\Message\ResponseInterface;

/**
 * iFrame API get-token response.
 *
 * On success this is a redirect: the customer is sent (full-page or via the
 * PayTR iframe) to https://www.paytr.com/odeme/guvenli/{token}. The actual
 * payment result arrives later on the Bildirim (notification) URL and is
 * validated with CompletePurchaseRequest.
 */
class IframeTokenResponse extends AbstractResponse implements RedirectResponseInterface
{
    public const IFRAME_URL = 'https://www.paytr.com/odeme/guvenli/';

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

                $this->response = new IframeTokenResponseModel($decoded);

            } catch (JsonException $e) {

                $this->response = new IframeTokenResponseModel([
                    'status' => Status::ERROR,
                    'reason' => $body,
                ]);

            }

        }
    }

    public function isSuccessful(): bool
    {
        return false;
    }

    public function isRedirect(): bool
    {
        return $this->response->status === Status::SUCCESS && ! empty($this->response->token);
    }

    public function getToken(): ?string
    {
        return $this->response->token;
    }

    public function getRedirectUrl(): string
    {
        return $this->isRedirect() ? self::IFRAME_URL . $this->response->token : '';
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    public function getRedirectData(): ?array
    {
        return null;
    }

    public function getMessage(): ?string
    {
        return $this->response->reason;
    }

    public function getData(): IframeTokenResponseModel
    {
        return $this->response;
    }
}
