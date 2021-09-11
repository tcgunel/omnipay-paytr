<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Helpers\Helper;

abstract class BaseModel
{
	public function __construct(?array $abstract)
	{
		foreach ($abstract as $key => $arg) {

			if (property_exists($this, $key)) {

				if (is_string($arg)) {

					$arg = trim($arg);

				}

				$this->$key = $arg;

			}

		}

		$this->formatFields();
	}

	private function formatFields()
	{
		$fields = [
			"expiry_month",
			"expiry_year",
			"cvv",
			"bin_number",
			"businessCard",
			"allow_non3d",
			"test_mode",
			"debug_on",
			"user_ip",
			"merchant_oid",
			"email",
			"user_name",
			"user_address",
			"user_phone",
			"user_basket",
			"installment_count",
			"payment_amount",
		];

		foreach ($fields as $field) {

			if (!empty($this->$field)) {

				$func = "format_{$field}";

				Helper::$func($this->$field, $this->$field);

			}

		}
	}

	abstract public function generateToken($salt, $key, $id);
}