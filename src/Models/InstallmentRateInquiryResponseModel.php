<?php

namespace Omnipay\Paytr\Models;

class InstallmentRateInquiryResponseModel extends BaseModel
{
	/**
	 * @var string
	 */
	public $status;

	/**
	 * @var string
	 */
	public $request_id;

	/**
	 * @var integer
	 */
	public $max_inst_non_bus;

	/**
	 * @var string
	 */
	public $err_msg;

	/**
	 * @var array[]
	 */
	public $oranlar;

	public function generateToken($salt, $key, $id)
	{
//		No need to generate token
	}
}