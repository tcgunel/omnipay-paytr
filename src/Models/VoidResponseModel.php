<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\Status;

/**
 * Response of a PayTR void/cancel (auth_type=cancel).
 */
class VoidResponseModel extends BaseModel
{
    /**
     * success veya error.
     *
     * @see Status
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $merchant_oid;

    /**
     * @var string
     */
    public $reference_no;

    /**
     * @var integer
     */
    public $is_test;

    /**
     * @var string
     */
    public $err_no;

    /**
     * @var string
     */
    public $err_msg;

    /**
     * @var string
     */
    public $reason;

    public function generateToken($salt, $key, $id)
    {
        // No token generated on a response.
    }
}
