<?php

namespace Omnipay\Paytr\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paytr\Constants\AuthType;
use Omnipay\Paytr\Constants\PaymentType;
use Omnipay\Paytr\Models\PurchaseRequestModel;
use Omnipay\Paytr\Traits\GettersSettersTrait;

/**
 * PayTR pre-authorization (ön provizyon).
 *
 * Unlike {@see PurchaseRequest}, which posts a browser form to /odeme, an
 * authorization is a server-to-server call to /odeme/auth carrying auth_type=preauth.
 * The request payload and token hash are otherwise identical to a purchase, so
 * we reuse PurchaseRequestModel (extended with auth_type / client_lang /
 * non3d_test_failed) and only override the transport + a couple of fields.
 *
 * secure=true  => 3D flow  (non_3d=0, requires returnUrl/cancelUrl, response carries html_content)
 * secure=false => non-3D   (non_3d=1, direct JSON result)
 */
class AuthorizeRequest extends PurchaseRequest
{
    use GettersSettersTrait;

    protected $endpoint = 'https://www.paytr.com/odeme/auth';

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     */
    public function getData()
    {
        $this->validateAll();

        $data = new PurchaseRequestModel([
            'merchant_id' => $this->getMerchantId(),
            'user_ip' => $this->getClientIp() ?? '127.0.0.1',
            'merchant_oid' => $this->getTransactionId(),
            'email' => $this->get_card('getEmail') ?? 'dummy-email@example.com',
            'payment_amount' => $this->getAmountInteger(),
            'payment_type' => $this->getPaymentType() ?? PaymentType::CARD,
            'installment_count' => $this->getInstallment(),

            'currency' => $this->getCurrency(),

            // test_mode and non_3d are BOTH part of the token hash and are BOTH posted
            // server-to-server via http_build_query. A bool/null hashes as "" but posts
            // as "0" (or is dropped), breaking the token — so both must be literal ints
            // evaluated before generateToken(). The backend passes testMode as a bool.
            'test_mode' => (int) (bool) $this->getTestMode(),
            'debug_on' => (int) (bool) $this->getTestMode(),

            'non_3d' => $this->getSecure() ? 0 : 1,

            'auth_type' => AuthType::PREAUTH,
            'client_lang' => $this->getClientLang() ?? 'tr',
            'non3d_test_failed' => (int) ($this->getNon3dTestFailed() ?? 0),

            'paytr_token' => '',

            'card_type' => $this->getCardSubBrand(),
            'cc_owner' => $this->get_card('getName'),
            'card_number' => $this->get_card('getNumber'),
            'expiry_month' => $this->get_card('getExpiryMonth'),
            'expiry_year' => $this->get_card('getExpiryYear'),
            'cvv' => $this->get_card('getCvv'),

            'merchant_ok_url' => $this->getReturnUrl(),
            'merchant_fail_url' => $this->getCancelUrl(),

            'user_name' => $this->get_card('getName') ?? '',
            'user_address' => implode(' ', [$this->get_card('getAddress1') ?? '', $this->get_card('getAddress2') ?? '']),
            'user_phone' => implode('', [$this->get_card('getPhoneExtension') ?? '', $this->get_card('getPhone') ?? '']),

            'user_basket' => $this->getItems(),

            'ctoken' => $this->getCardReference(),
            'utoken' => $this->getUserReference(),
            'store_card' => $this->getStoreCard(),

            'ref_id' => $this->getRefId(),
        ]);

        $data->generateToken(...$this->settings);

        return $data;
    }

    /**
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     */
    protected function validateAll(): void
    {
        $this->validateSettings();

        if ($this->getCardReference()) {

            $this->validate('userReference');

        } else {

            $this->getCard()->validate();

        }

        $this->validate('amount', 'currency', 'transactionId', 'installment', 'items');

        // PayTR pre-authorization is single payment only ("1 veya 0").
        if ((int) $this->getInstallment() > 1) {

            throw new \Omnipay\Common\Exception\InvalidRequestException(
                'PayTR pre-authorization does not support installments; installment must be 1.'
            );

        }

        // 3D flow needs the browser return URLs; the non-3D flow does not send them.
        if ($this->getSecure()) {

            $this->validate('returnUrl', 'cancelUrl');

        }
    }

    /**
     * @param PurchaseRequestModel $data
     * @return ResponseInterface|AuthorizeResponse
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

        // Response is built here (not via a createResponse() override) because the
        // parent PurchaseRequest::createResponse() is typed ": void" and cannot be
        // narrowed to return an AuthorizeResponse.
        return $this->response = new AuthorizeResponse($this, $httpResponse);
    }
}
