<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Helpers\Helper;

class RefundRequestModel extends BaseModel
{
	/**
	 * Mağaza no: PayTR tarafından size verilen Mağaza numarası
	 *
	 * @required
	 * @var integer
	 */
	public $merchant_id;

	/**
	 * Order id
	 *
	 * @required
	 * @var string
	 */
	public $merchant_oid;

	/**
	 * Refund amount.
	 * Float 2 decimal format. 10.00
	 *
	 * @required
	 * @var string
	 */
	public $return_amount;

	/**
	 * Paytr Token: İsteğin sizden geldiğine ve içeriğin değişmediğine emin olmamız için oluşturacağınız değerdir
	 *
	 * @required
	 * @var string
	 */
	public $paytr_token;

	public function generateToken($salt, $key, $id): void
	{
		$hash_string = $id . $this->merchant_oid . $this->return_amount . $salt;

		$this->paytr_token = Helper::hash($key, $hash_string);
	}
}