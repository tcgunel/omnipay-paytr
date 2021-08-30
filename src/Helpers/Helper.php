<?php

namespace Omnipay\Paytr\Helpers;

class Helper
{
	/**
	 * @param $input
	 * @param $var
	 */
	public static function format_cardExpireYear($input, &$var): void
	{
		$var = substr($input, -2);
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
	public static function format_bin_number($input, &$var)
	{
		$var = substr($input, 0, 6);
	}

	public static function hash($merchant_salt, $merchant_key, string $hash_string): string
	{
		return base64_encode(hash_hmac('sha256', $hash_string . $merchant_salt, $merchant_key, true));
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
