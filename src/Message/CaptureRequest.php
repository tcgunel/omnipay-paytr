<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paytr\Constants\AuthType;
use Omnipay\Paytr\Models\CaptureRequestModel;
use Omnipay\Paytr\Traits\GettersSettersTrait;

/**
 * Capture (provizyon kapama) a previously placed PayTR hold.
 *
 * Server-to-server POST to /odeme/auth with auth_type=capture. Mirrors
 * {@see RefundRequest}'s transport. Requires the reference_no returned on the
 * pre-authorization Bildirim (passed as transactionReference) and the same
 * merchant_oid used for the authorization (transactionId).
 */
class CaptureRequest extends RemoteAbstractRequest
{
    use GettersSettersTrait;

    protected $endpoint = 'https://www.paytr.com/odeme/auth';

    /**
     * @throws InvalidRequestException
     */
    public function getData(): CaptureRequestModel
    {
        $this->validateAll();

        $data = new CaptureRequestModel([
            'auth_type' => AuthType::CAPTURE,
            'merchant_id' => $this->getMerchantId(),
            'merchant_oid' => $this->getTransactionId(),
            'reference_no' => $this->getTransactionReference(),
            'payment_amount' => $this->getAmountInteger(),
            'client_ip' => $this->getClientIp(),
            'client_lang' => $this->getClientLang() ?? 'tr',
            'test_failed' => (int) ($this->getTestFailed() ?? 0),
            'paytr_token' => '',
        ]);

        $data->generateToken(...$this->settings);

        return $data;
    }

    /**
     * @throws InvalidRequestException
     */
    protected function validateAll(): void
    {
        $this->validateSettings();

        $this->validate('transactionId', 'transactionReference', 'amount', 'clientIp');
    }

    protected function createResponse($data): CaptureResponse
    {
        return $this->response = new CaptureResponse($this, $data);
    }

    /**
     * @param CaptureRequestModel $data
     * @return ResponseInterface|CaptureResponse
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->getEndpoint(),
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
            ],
            http_build_query($data, '', '&')
        );

        return $this->createResponse($httpResponse);
    }
}
