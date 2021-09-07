<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Helpers\Helper;

abstract class BaseModel
{
	public function __construct(?array $abstract)
	{
		foreach ($abstract as $key => $arg) {

			if (property_exists($this, $key)) {

				$this->$key = $arg;

			}

		}

		$this->formatFields();
	}

	private function formatFields()
	{
		$fields = ["cardExpireMonth", "cardExpireYear", "bin_number", "businessCard", "allow_non3d"];

		foreach ($fields as $field) {

			if (!empty($this->$field)) {

				$func = "format_{$field}";

				Helper::$func($this->$field, $this->$field);

			}

		}
	}

	abstract public function generateToken($salt, $key, $id);
}