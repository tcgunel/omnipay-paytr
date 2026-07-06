<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Helpers\Helper;

/**
 * Capture (auth_type=capture) of a previously placed PayTR hold.
 *
 * payment_amount is passed in kuruş and divided by 100 by
 * {@see BaseModel::formatFields()} (format_payment_amount), so both the hash and
 * the posted value use the same decimal string — matching the reference example.
 */
class CaptureRequestModel extends BaseModel
{
    /**
     * @required
     * @var string
     */
    public $auth_type;

    /**
     * @required
     * @var string
     */
    public $merchant_id;

    /**
     * Mağaza sipariş no (preauth ile aynı).
     *
     * @required
     * @var string
     */
    public $merchant_oid;

    /**
     * Preauth bildiriminde dönen referans numarası (A + 16 hane).
     *
     * @required
     * @var string
     */
    public $reference_no;

    /**
     * Çekilecek tutar. Nokta ayraçlı. 14.55
     *
     * @required
     * @var float
     */
    public $payment_amount;

    /**
     * @required
     * @var string
     */
    public $client_ip;

    /**
     * @var string
     */
    public $client_lang = 'tr';

    /**
     * Test amaçlı başarısız işlem simülasyonu (0 = normal).
     *
     * @var int
     */
    public $test_failed = 0;

    /**
     * @required
     * @var string
     */
    public $paytr_token;

    public function generateToken($salt, $key, $id)
    {
        $hash_string =
            $this->merchant_id .
            $this->client_ip .
            $this->merchant_oid .
            $this->reference_no .
            $this->payment_amount .
            $salt
        ;

        $this->paytr_token = Helper::hash($key, $hash_string);
    }
}
