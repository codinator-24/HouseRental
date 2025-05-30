<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | This is the default currency that will be used if no preference is
    | set by the user or in the session.
    |
    */
    'default' => 'USD',

    /*
    |--------------------------------------------------------------------------
    | Supported Currencies
    |--------------------------------------------------------------------------
    |
    | An array of currency codes that your application supports.
    |
    */
    'currencies' => ['USD', 'IQD'],

    /*
    |--------------------------------------------------------------------------
    | Exchange Rates
    |--------------------------------------------------------------------------
    |
    | Define the exchange rates relative to a base currency or directly.
    | Here, we define direct conversion rates.
    | USD_TO_IQD: How many IQD for 1 USD.
    | IQD_TO_USD: How many USD for 1 IQD.
    |
    */
    'exchange_rates' => [
        'USD_TO_IQD' => 1460,    // 1 USD = 1460 IQD
        'IQD_TO_USD' => 1 / 1460, // 1 IQD = 0.00068493150684932 USD (approx)
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Symbols and Formatting
    |--------------------------------------------------------------------------
    |
    | Define symbols and formatting rules for each currency.
    | 'symbol': The currency symbol.
    | 'format': A sprintf-compatible format string. %s for symbol, %v for value.
    | 'decimals': Number of decimal places for the value.
    | 'decimal_point': Character for the decimal point.
    | 'thousands_separator': Character for the thousands separator.
    |
    */
    'format' => [
        'USD' => [
            'symbol' => '$',
            'format' => '%s%v', // e.g., $150.00
            'decimals' => 2,
            'decimal_point' => '.',
            'thousands_separator' => ',',
        ],
        'IQD' => [
            'symbol' => 'IQD',
            'format' => '%v %s', // e.g., 219,000 IQD
            'decimals' => 0,
            'decimal_point' => '.',
            'thousands_separator' => ',',
        ],
    ],
];
