<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\Status;
use Omnipay\Paytr\Constants\Brand;
use Omnipay\Paytr\Constants\CardType;

class BinLookupResponseModel extends BaseModel
{
	/**
	 * Status: Sorgulama sonucu.
	 * success, error veya failed
	 *
	 * @see Status
	 * @var string
	 */
	public $status;

	/**
	 * Kart Türü: Kartın tipi
	 * credit, debit
	 *
	 * @see CardType
	 * @var string
	 */
	public $cardType;

	/**
	 * Şirket Kartı: Kartın şirket kartı olup olmadığı bilgisi
	 *
	 * @var boolean
	 */
	public $businessCard;

	/**
	 * Banka: Kartın bankası
	 * Örnek: Yapı Kredi
	 *
	 * @var string
	 */
	public $bank;

	/**
	 * Kart Program Ortaklığı İsmi: Kartın program ortaklığı ismi
	 * (Kart bir program ortaklığına dahil değil ise değer “none” olur.)
	 * Örnek: axess, bonus, cardfinans, combo, world, paraf, advantage, maximum
	 *
	 * @see Brand
	 * @var string
	 */
	public $brand;

	/**
	 * mastercard, visa etc.
	 *
	 * @var string
	 */
	public $schema;

	/**
	 * @var integer
	 */
	public $bankCode;

	/**
	 * @var boolean
	 */
	public $allow_non3d;

	/**
	 * Eğer sorguda bir hatanız varsa status değeri “error” döner.
	 * Bu durumda hata detayı için “err_msg” içeriğini kontrol etmelisiniz.
	 *
	 * @var string
	 */
	public $err_msg;

	public function generateToken($salt, $key, $id)
	{
//		No need to generate token
	}
}