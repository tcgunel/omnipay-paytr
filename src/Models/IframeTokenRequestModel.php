<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\Currency;
use Omnipay\Paytr\Helpers\Helper;

/**
 * iFrame API "1. Adım" (get-token) request payload.
 *
 * https://dev.paytr.com/iframe-api/iframe-api-1-adim
 *
 * Unlike the direct API (PurchaseRequestModel) this model does NOT run
 * BaseModel::formatFields(): the iframe endpoint expects payment_amount as an
 * integer (kuruş, amount × 100) and user_basket as base64(json) — both of which
 * the direct-API formatters would corrupt (÷100 and htmlentities respectively).
 * The caller (IframeTokenRequest) prepares those two fields in their final wire
 * form; here we only trim strings and normalise the currency alias.
 */
class IframeTokenRequestModel extends BaseModel
{
    /**
     * @required
     * @var string
     */
    public $merchant_id;

    /**
     * @required
     * @var string
     */
    public $user_ip;

    /**
     * @required
     * @var string
     */
    public $merchant_oid;

    /**
     * @required
     * @var string
     */
    public $email;

    /**
     * Sipariş tutarı × 100 (ör. 34.56 TL => 3456).
     *
     * @required
     * @var int
     */
    public $payment_amount;

    /**
     * @see Currency
     * @var string
     */
    public $currency = Currency::TL;

    /**
     * base64(json_encode([[name, price, quantity], ...])).
     *
     * @required
     * @var string
     */
    public $user_basket;

    /**
     * 0 = taksite izin ver, 1 = yalnızca tek çekim.
     *
     * @var int
     */
    public $no_installment = 0;

    /**
     * 0-12. 0 = sistem varsayılanı.
     *
     * @var int
     */
    public $max_installment = 0;

    /**
     * @var int
     */
    public $test_mode = 0;

    /**
     * @var int
     */
    public $debug_on = 0;

    /**
     * @required
     * @var string
     */
    public $paytr_token;

    /**
     * @var string
     */
    public $merchant_ok_url;

    /**
     * @var string
     */
    public $merchant_fail_url;

    /**
     * @var string
     */
    public $user_name = '';

    /**
     * @var string
     */
    public $user_address = '';

    /**
     * @var string
     */
    public $user_phone = '';

    /**
     * Ödeme sayfası zaman aşımı (dakika). Varsayılan 30.
     *
     * @var int
     */
    public $timeout_limit = 30;

    /**
     * "tr" veya "en".
     *
     * @var string
     */
    public $lang = 'tr';

    public function __construct(?array $abstract)
    {
        foreach ($abstract as $key => $arg) {

            if (property_exists($this, $key)) {

                if (is_string($arg)) {

                    $arg = trim($arg);

                }

                $this->$key = $arg;

            }

        }

        if ($this->currency === Currency::TRY) {

            $this->currency = Currency::TL;

        }
    }

    public function generateToken($salt, $key, $id)
    {
        $hash_str =
            $this->merchant_id .
            $this->user_ip .
            $this->merchant_oid .
            $this->email .
            $this->payment_amount .
            $this->user_basket .
            $this->no_installment .
            $this->max_installment .
            $this->currency .
            $this->test_mode
        ;

        $this->paytr_token = Helper::hash($key, $hash_str . $salt);
    }
}
