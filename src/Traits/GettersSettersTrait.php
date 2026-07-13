<?php

namespace Omnipay\Paytr\Traits;

trait GettersSettersTrait
{
    public function getRequestId()
    {
        return $this->getParameter('requestId');
    }

    public function setRequestId($value)
    {
        return $this->setParameter('requestId', $value);
    }

    public function getStoreCard()
    {
        return $this->getParameter('storeCard');
    }

    public function setStoreCard($value)
    {
        return $this->setParameter('storeCard', $value);
    }

    public function getFailReason()
    {
        return $this->getParameter('failReason');
    }

    public function setFailReason($value)
    {
        return $this->setParameter('failReason', $value);
    }

    public function getFailCode()
    {
        return $this->getParameter('failCode');
    }

    public function setFailCode($value)
    {
        return $this->setParameter('failCode', $value);
    }

    public function getTotalAmount()
    {
        return $this->getParameter('totalAmount');
    }

    public function setTotalAmount($value)
    {
        return $this->setParameter('totalAmount', $value);
    }

    public function getStatus()
    {
        return $this->getParameter('status');
    }

    public function setStatus($value)
    {
        return $this->setParameter('status', $value);
    }

    public function getCardSubBrand()
    {
        return $this->getParameter('cardSubBrand');
    }

    public function setCardSubBrand($value)
    {
        return $this->setParameter('cardSubBrand', $value);
    }

    public function getInstallment()
    {
        return $this->getParameter('installment');
    }

    public function setInstallment($value)
    {
        return $this->setParameter('installment', $value);
    }

    public function getPaymentType()
    {
        return $this->getParameter('paymentType');
    }

    public function setPaymentType($value)
    {
        return $this->setParameter('paymentType', $value);
    }

    public function getSecure()
    {
        return $this->getParameter('secure');
    }

    public function setSecure($value)
    {
        return $this->setParameter('secure', $value);
    }

    public function getUserReference()
    {
        return $this->getParameter('userReference');
    }

    public function setUserReference($value)
    {
        return $this->setParameter('userReference', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantKey()
    {
        return $this->getParameter('merchantKey');
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('merchantKey', $value);
    }

    public function getMerchantSalt()
    {
        return $this->getParameter('merchantSalt');
    }

    public function setMerchantSalt($value)
    {
        return $this->setParameter('merchantSalt', $value);
    }

    public function getClientIp()
    {
        return $this->getParameter('clientIp');
    }

    public function setClientIp($value)
    {
        return $this->setParameter('clientIp', $value);
    }

    public function getRefId()
    {
        return $this->getParameter('refId');
    }

    public function setRefId($value)
    {
        return $this->setParameter('refId', $value);
    }

    public function getClientLang()
    {
        return $this->getParameter('clientLang');
    }

    public function setClientLang($value)
    {
        return $this->setParameter('clientLang', $value);
    }

    public function getTestFailed()
    {
        return $this->getParameter('testFailed');
    }

    public function setTestFailed($value)
    {
        return $this->setParameter('testFailed', $value);
    }

    public function getNon3dTestFailed()
    {
        return $this->getParameter('non3dTestFailed');
    }

    public function setNon3dTestFailed($value)
    {
        return $this->setParameter('non3dTestFailed', $value);
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getNoInstallment()
    {
        return $this->getParameter('noInstallment');
    }

    public function setNoInstallment($value)
    {
        return $this->setParameter('noInstallment', $value);
    }

    public function getMaxInstallment()
    {
        return $this->getParameter('maxInstallment');
    }

    public function setMaxInstallment($value)
    {
        return $this->setParameter('maxInstallment', $value);
    }

    public function getTimeoutLimit()
    {
        return $this->getParameter('timeoutLimit');
    }

    public function setTimeoutLimit($value)
    {
        return $this->setParameter('timeoutLimit', $value);
    }

    public function getLang()
    {
        return $this->getParameter('lang');
    }

    public function setLang($value)
    {
        return $this->setParameter('lang', $value);
    }
}
