<?php

namespace Omnipay\Paytr\Helpers;

use JsonException;
use Omnipay\Common\Item;
use Omnipay\Common\ItemBag;

class Helper
{
	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_expiry_year($input, &$var): void
	{
		$var = substr($input, -2);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_expiry_month($input, &$var): void
	{
		$var = (int)$input;
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_gsm($input, &$var): void
	{
		$var = preg_replace("/(\D+)/", "", $input);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_cvv($input, &$var)
	{
		$var = substr($input, 0, 3);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_bin_number($input, &$var)
	{
		$var = substr($input, 0, 6);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_user_ip($input, &$var)
	{
		$var = substr($input, 0, 39);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_merchant_oid($input, &$var)
	{
		$var = substr($input, 0, 64);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_email($input, &$var)
	{
		$var = substr($input, 0, 100);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_cc_owner($input, &$var)
	{
		$var = substr($input, 0, 50);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_card_number($input, &$var)
	{
		$var = substr($input, 0, 16);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_user_name($input, &$var)
	{
		$var = substr($input, 0, 60);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_user_address($input, &$var)
	{
		$var = substr($input, 0, 400);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_user_phone($input, &$var)
	{
		$var = substr($input, 0, 20);
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_businessCard($input, &$var)
	{
		$input = strtolower($input);

		switch ($input) {
			case "y":
				$var = true;
				break;
			case "n":
				$var = false;
		}
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_allow_non3d($input, &$var)
	{
		$input = strtolower($input);

		switch ($input) {
			case "y":
				$var = true;
				break;
			case "n":
				$var = false;
		}
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_test_mode($input, &$var)
	{
		switch ($input) {
			case true:
				$var = 1;
				break;
			case false:
				$var = 0;
		}
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_debug_on($input, &$var)
	{
		switch ($input) {
			case true:
				$var = 1;
				break;
			case false:
				$var = 0;
		}
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_non_3d($input, &$var)
	{
		switch ($input) {
			case true:
				$var = 1;
				break;
			case false:
				$var = 0;
		}
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_installment_count($input, &$var)
	{
		$var = ((int)$input === 1) ? 0 : $input;
	}

	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_payment_amount($input, &$var)
	{
		$var = $input / 100;
	}

	/**
	 * @param ItemBag|null $input
	 * @param $var
	 * @throws JsonException
	 */
	public static function format_user_basket(?ItemBag $input, &$var)
	{
		if ($input) {

			$var = array_map(static function (Item $item) {

				return [$item->getName(), $item->getPrice(), $item->getQuantity()];

			}, $input->all());

			$var = htmlentities(json_encode($var, JSON_THROW_ON_ERROR));

		}
	}

	public static function hash($merchant_key, string $hash_string): string
	{
		return base64_encode(hash_hmac('sha256', $hash_string, $merchant_key, true));
	}

	public static function prettyPrint($data)
	{
		echo "<pre>" . print_r($data, true) . "</pre>";
	}

	/**
	 * @param object|array $input
	 */
	public static function arrayUnsetRecursive(&$input): void
	{
		foreach ($input as $key => $value) {

			if (is_array($value)) {

				self::arrayUnsetRecursive($value);

			} else if ($value === null) {

				if (is_object($input)) {

					unset($input->$key);

				} else {

					unset($input[$key]);

				}

			}

		}
	}
}
