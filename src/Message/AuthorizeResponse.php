<?php

namespace Omnipay\Paytr\Message;

use JsonException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Models\AuthorizeResponseModel;
use Psr\Http\Message\ResponseInterface;

/**
 * Response of a PayTR pre-authorization.
 *
 * Non-3D: isSuccessful() reflects the synchronous status.
 * 3D:     isRedirect() is true and getHtmlContent() returns the challenge page
 *         (there is no redirect URL — the page is self-contained and self-posting).
 *         The final result, together with the reference_no needed for capture/cancel,
 *         arrives asynchronously on the Bildirim URL, so getTransactionReference()
 *         is intentionally null here.
 */
class AuthorizeResponse extends AbstractResponse
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

                $this->response = new AuthorizeResponseModel($decoded);

            } catch (JsonException $e) {

                $this->response = new AuthorizeResponseModel([
                    'status' => Status::ERROR,
                    'err_msg' => $body,
                ]);

            }

        }
    }

    public function isSuccessful(): bool
    {
        // A 3D "success" only means the challenge page was issued, not that the
        // hold was placed — that is settled later on the Bildirim.
        return $this->response->status === Status::SUCCESS && ! $this->isRedirect();
    }

    public function isRedirect(): bool
    {
        return ! empty($this->response->html_content);
    }

    /**
     * The decoded 3D secure challenge page to echo to the browser, or null.
     */
    public function getHtmlContent(): ?string
    {
        return $this->isRedirect() ? base64_decode($this->response->html_content) : null;
    }

    public function getTransactionReference(): ?string
    {
        // PayTR does not return reference_no synchronously; it arrives on the Bildirim.
        return null;
    }

    public function getMessage(): ?string
    {
        return $this->response->err_msg ?? $this->response->reason;
    }

    public function getCode()
    {
        return $this->response->err_no;
    }

    public function getData(): AuthorizeResponseModel
    {
        return $this->response;
    }
}
