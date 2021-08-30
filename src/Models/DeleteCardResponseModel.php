<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\Status;

class DeleteCardResponseModel extends BaseModel
{
	/**
	 * Status: Silme sonucu.
	 * success, error veya failed
	 *
	 * @see Status
	 * @var string
	 */
	public $status;

	/**
	 * Hata kodu.
	 *
	 * @var string
	 */
	public $err_no;

	/**
	 * Eğer sorguda bir hatanız varsa status değeri “error” döner.
	 * Bu durumda hata detayı için “err_msg” içeriğini kontrol etmelisiniz.
	 *
	 * @var string
	 */
	public $err_msg;

	public function generateToken($salt, $key, $id)
	{
//		No need to generate token
	}
}