<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\BinStatus;
use Omnipay\Paytr\Constants\Brand;
use Omnipay\Paytr\Constants\CardType;
use Omnipay\Paytr\Constants\YesNo;

class BinLookupResponseModel extends BaseModel
{
	/**
	 * Status: Sorgulama sonucu.
	 * success, error veya failed
	 *
	 * @see BinStatus
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
	 * y / n
	 *
	 * @see YesNo
	 * @var string
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