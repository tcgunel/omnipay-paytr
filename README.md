[![License](https://poser.pugx.org/tcgunel/omnipay-paytr/license)](https://packagist.org/packages/tcgunel/omnipay-paytr)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen)](https://plant.treeware.earth/tcgunel/omnipay-paytr)
[![PHP Composer](https://github.com/tcgunel/omnipay-paytr/actions/workflows/tests.yml/badge.svg)](https://github.com/tcgunel/omnipay-paytr/actions/workflows/tests.yml)

# Omnipay Paytr Gateway
Omnipay gateway for Paytr - Direct API. All the methods of Paytr implemented for easy usage.

## Requirements
| PHP    | Package |
|--------|---------|
| ^7.3   | v1.0.0  |

## Installment

```
composer require tcgunel/omnipay-paytr
```

## Usage

Please see the [Wiki](https://github.com/tcgunel/omnipay-paytr/wiki) page for detailed usage of every method.

## Methods
#### Payment Services

* binLookup($options)
* purchase($options) // Purchase with 3D, without 3D, store user card with payment, purchase with stored cards.
* completePurchase($options) // Complete purchase.
* paymentInquiry($options) // Inquire payment.
* refund($options)

#### Wallet Services

* listCard($options)
* deleteCard($options)

#### Other Services

* installmentRates($options) // Available installment rates for your Paytr account.


## Tests
```
composer test
```
For windows:
```
vendor\bin\paratest.bat
```

## Treeware

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**](https://plant.treeware.earth/tcgunel/omnipay-paytr) to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.
