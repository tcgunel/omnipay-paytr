<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\Currency;
use Omnipay\Paytr\Constants\Status;

class PaymentInquiryResponseModel extends BaseModel
{
	public function __construct(?array $abstract)
	{
		parent::__construct($abstract);

		$this->payment_amount *= 100;

		if (!empty($this->returns)) {

			foreach ($this->returns as $key => $return) {

				$this->returns[$key] = new RefundResponseModel((array)$return);

			}

		}
	}

	/**
	 * @var boolean
	 */
	public $test_mode;

	/**
	 * Status: Sorgulama sonucu.
	 * success, error veya failed
	 *
	 * @see Status
	 * @var string
	 */
	public $status;

	/**
	 * Sipariş Tutarı
	 *
	 * @var string
	 */
	public $payment_amount;

	/**
	 * Müşteri ödeme tutarı
	 *
	 * @var string
	 */
	public $payment_total;

	/**
	 *
	 * @see Currency
	 * @var string
	 */
	public $currency;

	/**
	 * Siparişteki iadeler (varsa)
	 *
	 * @var RefundResponseModel[]
	 */
	public $returns;

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