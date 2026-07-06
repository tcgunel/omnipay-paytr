<?php

namespace Omnipay\Paytr\Constants;

/**
 * PayTR Direct API auth_type discriminator.
 *
 * All of preauth / capture / cancel share the same endpoint
 * (https://www.paytr.com/odeme/auth) and are told apart by this field.
 * A regular immediate sale uses the default 'auth' and is not modelled here
 * (the package exposes it through the redirect-based PurchaseRequest instead).
 */
class AuthType
{
    /** Ön provizyon: place a hold on the card without capturing. */
    public const PREAUTH = 'preauth';

    /** Provizyon kapama: capture a previously authorized hold. */
    public const CAPTURE = 'capture';

    /** Provizyon iptali: release a previously authorized hold. */
    public const CANCEL = 'cancel';
}
