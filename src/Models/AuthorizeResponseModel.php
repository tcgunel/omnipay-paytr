<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\Status;

/**
 * Response of a PayTR Direct API pre-authorization (auth_type=preauth).
 *
 * Non-3D: a synchronous JSON body with status=success|failed|error.
 * 3D:     status=success plus a base64 `html_content` challenge page that
 *         must be echoed to the browser; the real result then arrives on the
 *         Bildirim (notification) URL, which also carries the reference_no
 *         required for a later capture/cancel.
 */
class AuthorizeResponseModel extends BaseModel
{
    /**
     * success, failed veya error.
     *
     * @see Status
     * @var string
     */
    public $status;

    /**
     * Base64 encoded 3D secure challenge page (only present in the 3D flow).
     *
     * @var string
     */
    public $html_content;

    /**
     * Hata kodu.
     *
     * @var string
     */
    public $err_no;

    /**
     * Hata mesajı.
     *
     * @var string
     */
    public $err_msg;

    /**
     * Başarısız işlemlerde dönebilen açıklama.
     *
     * @var string
     */
    public $reason;

    public function generateToken($salt, $key, $id)
    {
        // No token generated on a response.
    }
}
