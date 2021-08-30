<?php

namespace Omnipay\Paytr\Tests;

use Faker\Factory;
use Omnipay\Tests\GatewayTestCase;

class TestCase extends GatewayTestCase
{
	public $faker;

	public function setUp(): void
	{
		parent::setUp();

		$this->faker = Factory::create("tr_TR");
	}
}
