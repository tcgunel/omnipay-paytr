<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Helpers\Helper;

class BinLookupRequestModel extends BaseModel
{
	/**
	 * Mağaza no: PayTR tarafından size verilen Mağaza numarası
	 *
	 * @required
	 * @var integer
	 */
	public $merchant_id;

	/**
	 * BIN Numarası: Kart numarasının ilk 6 hanesi
	 *
	 * @required
	 * @var string
	 */
	public $bin_number;

	/**
	 * Paytr Token: İsteğin sizden geldiğine ve içeriğin değişmediğine emin olmamız için oluşturacağınız değerdir
	 *
	 * @required
	 * @var string
	 */
	public $paytr_token;

	public function generateToken($salt, $key, $id): void
	{
		$hash_string = $this->bin_number . $id . $salt;

		$this->paytr_token = Helper::hash($salt, $key, $hash_string);
	}
}