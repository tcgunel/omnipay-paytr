<?php

namespace Omnipay\Paytr\Models;

use Omnipay\Paytr\Constants\CardSubBrand;
use Omnipay\Paytr\Constants\Currency;
use Omnipay\Paytr\Constants\PaymentType;
use Omnipay\Paytr\Helpers\Helper;

class PurchaseRequestModel extends BaseModel
{
    public function __construct(?array $abstract)
    {
        parent::__construct($abstract);

	    // Purchase requests require non-standard currency value.
		if (isset($this->currency) && $this->currency === "TRY"){

			$this->currency = "TL";

		}
    }

    /**
     * @required
     * @var string
     */
    public $merchant_id;

    /**
     * @required
     * @var string
     */
    public $user_ip;

    /**
     * @required
     * @var string
     */
    public $merchant_oid;

    /**
     * @required
     * @var string
     */
    public $email = "dummy-email@example";

    /**
     * Ayraç olarak yalnızca nokta (.) gönderilmelidir.
     *
     * @required
     * @var float
     */
    public $payment_amount;

    /**
     * @see PaymentType
     * @required
     * @var string
     */
    public $payment_type;

    /**
     * 0 2 3 4 5 6 7 8 9 10 11 12
     *
     * @required
     * @var int
     */
    public $installment_count = 0;

    /**
     * @see Currency
     * @var string
     */
    public $currency;

    /**
     * @var int
     */
    public $test_mode = 0;

    /**
     * @var int
     */
    public $debug_on = 0;

    /**
     * @var int
     */
    public $non_3d = 0;

    /**
     * @required
     * @var string
     */
    public $paytr_token;

    /**
     * Kart tipi (Taksitli işlemlerde kullanmak üzere)
     *
     * @see CardSubBrand
     * @var string
     */
    public $card_type;

    /**
     * @required
     * @var string
     */
    public $cc_owner;

    /**
     * @required
     * @var string
     */
    public $card_number;

    /**
     * @required
     * @var string
     */
    public $expiry_month;

    /**
     * @required
     * @var string
     */
    public $expiry_year;

    /**
     * @required
     * @var string
     */
    public $cvv;

    /**
     * @required
     * @var string
     */
    public $merchant_ok_url;

    /**
     * @required
     * @var string
     */
    public $merchant_fail_url;

    /**
     * @required
     * @var string
     */
    public $user_name = "";

    /**
     * @required
     * @var string
     */
    public $user_address = "";

    /**
     * @required
     * @var string
     */
    public $user_phone = "";

    /**
     * @required
     * @var string
     */
    public $user_basket = "";

    /**
     * UTOKEN GÖNDERİLMEDİĞİ DURUMDA, BU KULLANICIYA AİT DAHA ÖNCEDEN KAYDEDİLMİŞ BİR KART OLMADIĞI VARSAYILIR
     * VE PAYTR TARAFINDA YENİ BİR UTOKEN OLUŞTURULARAK ÖDEME İŞLEMİNİN CEVABINDA DÖNDÜRÜLÜR (BİLDİRİM URL'YE)!
     * EĞER KULLANICI SİSTEMİNİZDE DAHA ÖNCE BİR KART KAYDETMİŞSE TARAFINIZDA KAYITLI UTOKEN PARAMETRESİNİ POST İÇERİĞİNE EKLEMELİSİNİZ.
     * BÖYLECE BU KART DA AYNI KULLANICIYA TANIMLANACAKTIR. EĞER MEVCUT KULLANICI İÇİN YENİ BİR KART
     * TANIMI YAPILACAĞI HALDE MEVCUT UTOKEN GÖNDERİLMEZSE YENİ BİR UTOKEN OLUŞTURULACAĞINDAN KULLANICININ TÜM KARTLARI TEK BİR UTOKEN ALTINDA GRUPLANMAZ!!!
     *
     * @var string
     */
    public $utoken;

	/**
	 * Card reference id.
	 *
	 * @var string
	 */
    public $ctoken;

	/**
	 * İş ortaklığı ref id.
	 *
	 * @var string
	 */
    public $ref_id;

	/**
	 * @var boolean
	 */
    public $store_card;


	public function generateToken($salt, $key, $id)
	{
		$hash_string =
			$this->merchant_id .
			$this->user_ip .
			$this->merchant_oid .
			$this->email .
			$this->payment_amount .
			$this->payment_type .
			$this->installment_count .
			$this->currency .
			$this->test_mode .
			$this->non_3d .
			$salt
		;

		$this->paytr_token = Helper::hash($key, $hash_string);
	}
}
