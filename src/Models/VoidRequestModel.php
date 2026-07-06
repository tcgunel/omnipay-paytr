<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Helpers\Helper;

/**
 * Void / cancel (auth_type=cancel) of a previously placed PayTR hold.
 *
 * Identical to {@see CaptureRequestModel} minus the amount: cancelling releases
 * the full hold, so no payment_amount participates in the payload or the hash.
 */
class VoidRequestModel extends BaseModel
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
     * @required
     * @var string
     */
    public $merchant_oid;

    /**
     * @required
     * @var string
     */
    public $reference_no;

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
            $salt
        ;

        $this->paytr_token = Helper::hash($key, $hash_string);
    }
}
