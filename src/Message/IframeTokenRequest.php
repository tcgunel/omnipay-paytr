<?php

namespace Omnipay\Paytr\Message;

use JsonException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Item;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paytr\Constants\Currency;
use Omnipay\Paytr\Models\IframeTokenRequestModel;

/**
 * iFrame API "1. Adım": request a payment token from
 * https://www.paytr.com/odeme/api/get-token
 *
 * On success PayTR returns {status: success, token}. The customer is then sent
 * to https://www.paytr.com/odeme/guvenli/{token} (embedded in an iframe or as a
 * full-page redirect). See IframeTokenResponse for the redirect wiring.
 *
 * https://dev.paytr.com/iframe-api/iframe-api-1-adim
 */
class IframeTokenRequest extends RemoteAbstractRequest
{
    protected $endpoint = 'https://www.paytr.com/odeme/api/get-token';

    /**
     * @throws InvalidRequestException|JsonException
     */
    public function getData(): IframeTokenRequestModel
    {
        $this->validateAll();

        $data = new IframeTokenRequestModel([
            'merchant_id' => $this->getMerchantId(),
            'user_ip' => $this->getClientIp() ?? '127.0.0.1',
            'merchant_oid' => $this->getTransactionId(),
            'email' => $this->get_card('getEmail') ?? 'dummy-email@example.com',
            'payment_amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency() ?? Currency::TL,
            'user_basket' => $this->buildUserBasket(),

            'no_installment' => (int) ($this->getNoInstallment() ?? 0),
            'max_installment' => (int) ($this->getMaxInstallment() ?? 0),

            'test_mode' => $this->getTestMode() ? 1 : 0,
            'debug_on' => $this->getTestMode() ? 1 : 0,

            'paytr_token' => '',

            'merchant_ok_url' => $this->getReturnUrl(),
            'merchant_fail_url' => $this->getCancelUrl(),

            'user_name' => $this->get_card('getName') ?? '',
            'user_address' => trim(implode(' ', [$this->get_card('getAddress1') ?? '', $this->get_card('getAddress2') ?? ''])),
            'user_phone' => implode('', [$this->get_card('getPhoneExtension') ?? '', $this->get_card('getPhone') ?? '']),

            'timeout_limit' => (int) ($this->getTimeoutLimit() ?? 30),
            'lang' => $this->getLang() ?? 'tr',
        ]);

        $data->generateToken(...$this->settings);

        return $data;
    }

    /**
     * Base64-encoded JSON basket the iframe endpoint (and the paytr_token hash)
     * expect: [[name, price, quantity], ...].
     *
     * @throws JsonException
     */
    protected function buildUserBasket(): string
    {
        $items = $this->getItems();

        $basket = [];

        if ($items) {

            $basket = array_map(static function (Item $item) {

                return [$item->getName(), $item->getPrice(), $item->getQuantity()];

            }, $items->all());

        }

        return base64_encode(json_encode($basket, JSON_THROW_ON_ERROR));
    }

    /**
     * @throws InvalidRequestException
     */
    protected function validateAll(): void
    {
        $this->validateSettings();

        $this->validate('amount', 'transactionId', 'items');
    }

    protected function createResponse($data): IframeTokenResponse
    {
        return $this->response = new IframeTokenResponse($this, $data);
    }

    /**
     * @param IframeTokenRequestModel $data
     * @return ResponseInterface|IframeTokenResponse
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
