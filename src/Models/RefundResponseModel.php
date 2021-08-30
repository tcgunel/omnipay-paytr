<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\Status;

class RefundResponseModel extends BaseModel
{
	/**
	 * Status: Sorgulama sonucu.
	 * success, error veya failed
	 *
	 * @see Status
	 * @var string
	 */
	public $status;

	/**
	 * İade talebi test işlem içinse 1 döner.
	 *
	 * @var integer
	 */
	public $is_test;

	/**
	 * İade talebi yapılan sipariş numarası.
	 *
	 * @var string
	 */
	public $merchant_oid;

	/**
	 * İade talebi yapılan tutar.
	 *
	 * @var string
	 */
	public $return_amount;

	/**
	 * Hata kodu
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