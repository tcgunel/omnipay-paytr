<?php

namespace Omnipay\Paytr\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Item;
use Omnipay\Common\ItemBag;
use Omnipay\Paytr\Message\PurchaseRequest;
use Omnipay\Paytr\Message\PurchaseResponse;
use Omnipay\Paytr\Models\PurchaseRequestModel;
use Omnipay\Paytr\Tests\TestCase;

class PurchaseTest extends TestCase
{
	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws InvalidCreditCardException
	 * @throws \JsonException
	 */
	public function test_purchase_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PurchaseRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$expected = new PurchaseRequestModel([
			'merchant_id'       => 'id',
			'user_ip'           => '127.0.0.1',
			'merchant_oid'      => '6123ad8ba9cb76123ad8ba9cb9',
			'email'             => 'dummy-email@example.com',
			'payment_amount'    => 1234,
			'payment_type'      => 'card',
			'installment_count' => 0,
			'currency'          => null,
			'test_mode'         => null,
			'debug_on'          => null,
			'non_3d'            => false,
			'paytr_token'       => 'kBtuw4UVRmzoGghbUzFqmBPd0DYEfQJaqgBEsqoDmkg=',
			'card_type'         => null,
			'cc_owner'          => 'Example User',
			'card_number'       => '9792030394440796',
			'expiry_month'      => 12,
			'expiry_year'       => '99',
			'cvv'               => '000',
			'merchant_ok_url'   => 'https://iparatest.com/omnipay/paytr/usage/payment-success.php',
			'merchant_fail_url' => 'https://iparatest.com/omnipay/paytr/usage/payment-failure.php',
			'user_name'         => 'Example User',
			'user_address'      => '123 Billing St Billsville',
			'user_phone'        => '5554443322',
			'user_basket'       => new ItemBag(
				[
					new Item([
						"name"        => "Perspiciatis et facilis tempore facilis.",
						"description" => "My notion was that she was talking. 'How CAN I have done that?' she thought. 'I must be a LITTLE larger, sir, if you like,' said the King and Queen of Hearts, carrying the King's crown on a.",
						"quantity"    => 1,
						"price"       => 12.34,
					])
				]
			),
			'utoken'            => null,
			'ctoken'            => null,
			'store_card'        => null,
		]);

		self::assertEquals($expected, $data);
	}

	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws InvalidCreditCardException
	 * @throws \JsonException
	 */
	public function test_purchase_store_card_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PurchaseStoreCardRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$expected = new PurchaseRequestModel([
			'merchant_id'       => 'id',
			'user_ip'           => '127.0.0.1',
			'merchant_oid'      => '6123ad8ba9cb76123ad8ba9cb9',
			'email'             => 'dummy-email@example.com',
			'payment_amount'    => 1234,
			'payment_type'      => 'card',
			'installment_count' => 0,
			'currency'          => null,
			'test_mode'         => null,
			'debug_on'          => null,
			'non_3d'            => false,
			'paytr_token'       => 'kBtuw4UVRmzoGghbUzFqmBPd0DYEfQJaqgBEsqoDmkg=',
			'card_type'         => null,
			'cc_owner'          => 'Example User',
			'card_number'       => '9792030394440796',
			'expiry_month'      => 12,
			'expiry_year'       => '99',
			'cvv'               => '000',
			'merchant_ok_url'   => 'https://iparatest.com/omnipay/paytr/usage/payment-success.php',
			'merchant_fail_url' => 'https://iparatest.com/omnipay/paytr/usage/payment-failure.php',
			'user_name'         => 'Example User',
			'user_address'      => '123 Billing St Billsville',
			'user_phone'        => '5554443322',
			'user_basket'       => new ItemBag(
				[
					new Item([
						"name"        => "Perspiciatis et facilis tempore facilis.",
						"description" => "My notion was that she was talking. 'How CAN I have done that?' she thought. 'I must be a LITTLE larger, sir, if you like,' said the King and Queen of Hearts, carrying the King's crown on a.",
						"quantity"    => 1,
						"price"       => 12.34,
					])
				]
			),
			'utoken'            => null,
			'ctoken'            => null,
			'store_card'        => true,
		]);

		self::assertEquals($expected, $data);
	}

	/**
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 * @throws InvalidCreditCardException
	 * @throws \JsonException
	 */
	public function test_purchase_with_stored_card_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PurchaseWithStoredCardRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$expected = new PurchaseRequestModel([
			'merchant_id'       => 'id',
			'user_ip'           => '127.0.0.1',
			'merchant_oid'      => '6123ad8ba9cb76123ad8ba9cb9',
			'email'             => 'dummy-email@example.com',
			'payment_amount'    => 4567,
			'payment_type'      => 'card',
			'installment_count' => 0,
			'currency'          => null,
			'test_mode'         => null,
			'debug_on'          => null,
			'non_3d'            => false,
			'paytr_token'       => 'cPlFzneoo0/Mz5UA6HXq5YFkEeqM/YvAGuu4x6SqncQ=',
			'card_type'         => null,
			'cc_owner'          => null,
			'card_number'       => null,
			'expiry_month'      => null,
			'expiry_year'       => null,
			'cvv'               => null,
			'merchant_ok_url'   => 'https://iparatest.com/omnipay/paytr/usage/payment-success.php',
			'merchant_fail_url' => 'https://iparatest.com/omnipay/paytr/usage/payment-failure.php',
			'user_name'         => '',
			'user_address'      => '',
			'user_phone'        => '',
			'user_basket'       => new ItemBag(
				[
					new Item([
						"name"        => "Perspiciatis et facilis tempore facilis.",
						"description" => "My notion was that she was talking. 'How CAN I have done that?' she thought. 'I must be a LITTLE larger, sir, if you like,' said the King and Queen of Hearts, carrying the King's crown on a.",
						"quantity"    => 1,
						"price"       => 45.67,
					])
				]
			),
			'utoken'            => 'userReference',
			'ctoken'            => 'cardReference',
			'store_card'        => null,
		]);

		self::assertEquals($expected, $data);
	}

	public function test_purchase_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PurchaseRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_purchase_response()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PurchaseRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		/** @var PurchaseResponse $response */
		$response = $request->initialize($options)->send();

		$data = $response->getRedirectData();

		$this->assertFalse($response->isSuccessful());

		$this->assertTrue($response->isRedirect());

		$this->assertEquals((array)$request->getData(), $data);
	}
}
