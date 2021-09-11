<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Helpers\Helper;

class InstallmentRateInquiryRequestModel extends BaseModel
{
	/**
	 * Mağaza no: PayTR tarafından size verilen Mağaza numarası
	 *
	 * @required
	 * @var integer
	 */
	public $merchant_id;

	/**
	 * User reference id.
	 *
	 * @required
	 * @var string
	 */
	public $request_id;

	/**
	 * Paytr Token: İsteğin sizden geldiğine ve içeriğin değişmediğine emin olmamız için oluşturacağınız değerdir
	 *
	 * @required
	 * @var string
	 */
	public $paytr_token;

	public function generateToken($salt, $key, $id): void
	{
		$hash_string = $id . $this->request_id . $salt;

		$this->paytr_token = Helper::hash($key, $hash_string);
	}
}