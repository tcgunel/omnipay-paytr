<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paytr\Constants\AuthType;
use Omnipay\Paytr\Models\VoidRequestModel;
use Omnipay\Paytr\Traits\GettersSettersTrait;

/**
 * Void / cancel (provizyon iptali) a previously placed PayTR hold.
 *
 * Server-to-server POST to /odeme/auth with auth_type=cancel. Same shape as
 * {@see CaptureRequest} without an amount — cancelling releases the whole hold.
 */
class VoidRequest extends RemoteAbstractRequest
{
    use GettersSettersTrait;

    protected $endpoint = 'https://www.paytr.com/odeme/auth';

    /**
     * @throws InvalidRequestException
     */
    public function getData(): VoidRequestModel
    {
        $this->validateAll();

        $data = new VoidRequestModel([
            'auth_type' => AuthType::CANCEL,
            'merchant_id' => $this->getMerchantId(),
            'merchant_oid' => $this->getTransactionId(),
            'reference_no' => $this->getTransactionReference(),
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

        $this->validate('transactionId', 'transactionReference', 'clientIp');
    }

    protected function createResponse($data): VoidResponse
    {
        return $this->response = new VoidResponse($this, $data);
    }

    /**
     * @param VoidRequestModel $data
     * @return ResponseInterface|VoidResponse
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
