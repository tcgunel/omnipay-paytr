<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Helpers\Helper;

class DeleteCardRequestModel extends BaseModel
{
	/**
	 * Mağaza no: PayTR tarafından size verilen Mağaza numarası
	 *
	 * @required
	 * @var integer
	 */
	public $merchant_id;

	/**
	 * Card reference id. Given by provider.
	 *
	 * @required
	 * @var string
	 */
	public $ctoken;

	/**
	 * User reference id. Given by provider.
	 *
	 * @required
	 * @var string
	 */
	public $utoken;

	/**
	 * Paytr Token: İsteğin sizden geldiğine ve içeriğin değişmediğine emin olmamız için oluşturacağınız değerdir
	 *
	 * @required
	 * @var string
	 */
	public $paytr_token;

	public function generateToken($salt, $key, $id): void
	{
		$hash_string = $this->ctoken . $this->utoken;

		$this->paytr_token = Helper::hash($salt, $key, $hash_string);
	}
}