<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\Status;

/**
 * iFrame API get-token response payload.
 *
 * Success: {"status":"success","token":"..."}
 * Failure: {"status":"failed","reason":"..."}
 */
class IframeTokenResponseModel extends BaseModel
{
    /**
     * success veya failed.
     *
     * @see Status
     * @var string
     */
    public $status;

    /**
     * Başarılı yanıtta dönen iframe token'ı.
     *
     * @var string
     */
    public $token;

    /**
     * Başarısız yanıtta hata açıklaması.
     *
     * @var string
     */
    public $reason;

    public function generateToken($salt, $key, $id)
    {
        //		No need to generate token
    }
}
