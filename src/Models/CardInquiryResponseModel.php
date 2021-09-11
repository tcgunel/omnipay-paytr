<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\CardSubBrand;
use Omnipay\Paytr\Constants\CardType;

class CardInquiryResponseModel extends BaseModel
{
	/**
	 * Card reference id.
	 *
	 * @var string
	 */
	public $ctoken;

	/**
	 * @var integer
	 */
	public $last_4;

	/**
	 * @var integer
	 */
	public $month;

	/**
	 * @var integer
	 */
	public $year;

	/**
	 * @var string
	 */
	public $c_bank;

	/**
	 * @var boolean
	 */
	public $require_cvv;

	/**
	 * @var string
	 */
	public $c_name;

	/**
	 * @see CardSubBrand
	 * @var string
	 */
	public $c_brand;

	/**
	 * @see CardType
	 * @var string
	 */
	public $c_type;

	/**
	 * @var boolean
	 */
	public $businessCard;

	/**
	 * @var integer
	 */
	public $initial;

	/**
	 * VISA, MASTERCARD, TROY...
	 *
	 * @var string
	 */
	public $schema;

	/**
	 * @var string
	 */
	public $status;

	/**
	 * @var string
	 */
	public $err_msg;

	public function generateToken($salt, $key, $id)
	{
//		No need to generate token
	}
}