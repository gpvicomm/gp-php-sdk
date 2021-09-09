# Vicomm PHP SDK

### Installation

Install via composer (not hosted in packagist yet)

`composer require vicomm/sdk`

## Usage

```php
<?php

require 'vendor/autoload.php';

use Vicomm\Vicomm;

// First setup your credentials provided by vicomm
$applicationCode = "SOME_APP_CODE";
$applicationKey = "SOME_APP_KEY";

Vicomm::init($applicationCode, $applicationKey);
```

Once time are set your credentials, you can use available resources.

Resources availables:

- **Card** 
 * Available methods: `getList`, `delete`
- **Charge**
 * Available methods: `create`, `authorize`, `capture`, `verify`, `refund`
- **Cash**
 * Available methods: `generateOrder`

### Card

See full documentation of these features [here](https://developers.gpvicomm.com/api/?shell#metodos-de-pago-tarjetas).

#### List

```php
<?php

use Vicomm\Vicomm;
use Vicomm\Exceptions\VicommErrorException;

Vicomm::init($applicationCode, $aplicationKey);

$card = Vicomm::card();

// Success response
$userId = "1";
$listOfUserCards = $card->getList($userId);

$totalSizeOfCardList = $listOfUserCards->result_size;
$listCards = $listOfUserCards->cards;

// Get all data of response
$response = $listOfUserCards->getData();

// Catch fail response
try {
	$listOfUserCards = $card->getList("someUID");
} catch (VicommErrorException $error) {
	// Details of exception
	echo $error->getMessage();
	// You can see the logs for complete information
}
```

### Charges

See full documentation of these features [here](https://developers.gpvicomm.com/api/?shell#metodos-de-pago-tarjetas).

#### Create new charge

See full documentation about this [here](https://developers.gpvicomm.com/api/?shell#metodos-de-pago-tarjetas-cobro-con-tarjeta-credito)

```php
<?php

use Vicomm\Vicomm;
use Vicomm\Exceptions\VicommErrorException;

// Card token
$cardToken = "myAwesomeTokenCard";

$charge = Vicomm::charge();

$userDetails = [
    'id' => "1", // Field required
    'email' => "cbenavides@gpvicomm.com" // Field required
];

$orderDetails = [
    'amount' => 100.00, // Field required
    'description' => "XXXXXX", // Field required
    'dev_reference' => "XXXXXX", // Field required
    'vat' => 0.00 // Field required 
];

try {
    $created = $charge->create($cardToken, $orderDetails, $userDetails);
} catch (VicommErrorException $error) {
    // See the console output for complete information
    // Access to HTTP code from vicomm service
    $code = $error->getCode();
    $message = $error->getMessage();
}

// Get transaction status
$status = $created->transaction->status;
// Get transaction ID
$transactionId = $created->transaction->id;
// Get authorization code
$authCode = $created->transaction->authorization_code;
```

#### Authorize charge

See the full documentation [here](https://developers.gpvicomm.com/api/?shell#metodos-de-pago-tarjetas-autorizar)

```php
<?php

use Vicomm\Vicomm;
use Vicomm\Exceptions\VicommErrorException;

// Card token
$cardToken = "myAwesomeTokenCard";

$charge = Vicomm::charge();

$userDetails = [
    'id' => "1", // Field required
    'email' => "cbenavides@gpvicomm.com" // Field required
];

$orderDetails = [
    'amount' => 100.00, // Field required
    'description' => "XXXXXX", // Field required
    'dev_reference' => "XXXXXX", // Field required
    'vat' => 0.00 // Field required 
];

try {
    $authorization = $charge->authorize($cardToken, $orderDetails, $userDetails);
} catch (VicommErrorException $error) {
    // See the console output for complete information
    // Access to HTTP code from vicomm service
    $code = $error->getCode();
    $message = $error->getMessage();
}

// Get transaction status
$status = $authorization->transaction->status;
// Get transaction ID
$transactionId = $authorization->transaction->id;
// Get authorization code
$authCode = $authorization->transaction->authorization_code;
```

#### Capture

See the full documentation [here](https://developers.gpvicomm.com/api/?shell#metodos-de-pago-tarjetas-captura)

Need make a [authorization process](#authorize-charge)

````php
<?php

use Vicomm\Vicomm;
use Vicomm\Exceptions\VicommErrorException;

$charge = Vicomm::charge();

$authorization = $charge->authorize($cardToken, $orderDetails, $userDetails);
$transactionId = $authorization->transaction->id;

try {
    $capture = $charge->capture($transactionId);
} catch (VicommErrorException $error) {
    // See the console output for complete information
    // Access to HTTP code from vicomm service
    $code = $error->getCode();
    $message = $error->getMessage();
}

// Get transaction status
$status = $capture->transaction->status;

// Make a capture with different amount
$newAmountForCapture = 1000.46;
$capture = $charge->capture($transactionId, $newAmountForCapture);
````

#### Refund

See the full documentation [here](https://developers.gpvicomm.com/api/?shell#metodos-de-pago-tarjetas-reembolso)

Need make a [create process](https://developers.gpvicomm.com/api/?shell#metodos-de-pago-tarjetas-cobro-con-tarjeta-credito)

````php
<?php

use Vicomm\Vicomm;
use Vicomm\Exceptions\vicommErrorException;

$charge = Vicomm::charge();

$created = $charge->create($cardToken, $orderDetails, $userDetails);
$transactionId = $created->transaction->id;

try {
    $refund = $charge->refund($transactionId);
} catch (VicommErrorException $error) {
    // See the console output for complete information
    // Access to HTTP code from vicomm service
    $code = $error->getCode();
    $message = $error->getMessage();
}

// Get status of refund
$status = $refund->status;
$detail = $refund->detail;

// Make a partial refund
$partialAmountToRefund = 10;
$refund = $charge->refund($transactionId, $partialAmountToRefund);
````

### Cash

#### Generate order

See the all available options in [here](https://developers.gpvicomm.com/api/?shell#metodos-de-pago-efectivo-generar-una-referencia)

```php
<?php

use Vicomm\Vicomm;
use Vicomm\Exceptions\VicommErrorException;

$cash = Vicomm::cash();

$carrierDetails = [
    'id' => 'oxxo', // Field required
    'extra_params' => [ // Depends of carrier, for oxxo is required
        'user' => [ // For oxxo is required
            'name' => "Juan",
            'last_name' => "Perez"
        ]
    ]
];

$userDetails = [
   'id' => "1", // Field required
   'email' => "randm@mail.com" // Field required
];

$orderDetails = [
    'dev_reference' => "XXXXXXX", // Field required 
    'amount' => 100, // Field required
    'expiration_days' => 1, // Field required
    'recurrent' => false, // Field required
    'description' => "XXXXXX" // Field required
];

try {
    $order = $cash->generateOrder($carrierDetails, 
    $userDetails, 
    $orderDetails);
} catch (VicommErrorException $error) {
    // See the console output for complete information
    // Access to HTTP code from vicomm service
    $code = $error->getCode();
    $message = $error->getMessage();
}

// Get reference code
$referenceCode = $order->transaction->reference;
// Get expiration date
$expirationData = $order->transaction->expiration_date;
// Get order status
$status = $order->transaction->status;
```

### Run unit tests

`composer run test`