<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Models\CurrencyRate; // Added import

class SetCurrencyContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the currency configuration
        $currencyConfig = Config::get('currency');

        if (!$currencyConfig) {
            // Fallback or error handling if config/currency.php is missing or empty
            // For now, we'll assume it always exists as per our setup.
            // You might want to log an error here in a production scenario.
            return $next($request);
        }

        // Determine the current currency
        // 1. Check session
        // 2. Fallback to default from config
        $currentCurrency = Session::get('currency', $currencyConfig['default']);

        // Ensure the session currency is a valid one from our config
        if (!in_array($currentCurrency, $currencyConfig['currencies'])) {
            $currentCurrency = $currencyConfig['default'];
            Session::put('currency', $currentCurrency); // Correct session if invalid
        }

        // Fetch dynamic exchange rate from database
        $dynamicRate = CurrencyRate::where('rate_name', 'USD_TO_IQD')->first();
        if ($dynamicRate && isset($currencyConfig['exchange_rates'])) {
            $currencyConfig['exchange_rates']['USD_TO_IQD'] = (float) $dynamicRate->rate_value;
            if ((float) $dynamicRate->rate_value > 0) {
                $currencyConfig['exchange_rates']['IQD_TO_USD'] = 1 / (float) $dynamicRate->rate_value;
            } else {
                // Handle division by zero or invalid rate, fallback to config default or a safe value
                $defaultIqdToUsd = Config::get('currency.exchange_rates.IQD_TO_USD');
                $currencyConfig['exchange_rates']['IQD_TO_USD'] = $defaultIqdToUsd ?: (1/1460); // Fallback
            }
        }

        // Share currency data with all views
        View::share('currentCurrency', $currentCurrency);
        View::share('currencyConfig', $currencyConfig);
        View::share('activeCurrencyFormat', $currencyConfig['format'][$currentCurrency] ?? $currencyConfig['format'][$currencyConfig['default']]);


        // Helper function for formatting prices, can be shared or used in a service/helper class
        View::composer('*', function ($view) use ($currentCurrency, $currencyConfig) {
            $view->with('formatPrice', function ($amount, $currencyCode = null) use ($currentCurrency, $currencyConfig) {
                $targetCurrency = $currencyCode ?: $currentCurrency;
                
                if (!isset($currencyConfig['format'][$targetCurrency])) {
                    // Fallback to default currency format if the target is somehow invalid
                    $targetCurrency = $currencyConfig['default'];
                }

                $formatDetails = $currencyConfig['format'][$targetCurrency];
                
                $formattedValue = number_format(
                    (float)$amount,
                    $formatDetails['decimals'],
                    $formatDetails['decimal_point'],
                    $formatDetails['thousands_separator']
                );

                return str_replace(['%s', '%v'], [$formatDetails['symbol'], $formattedValue], $formatDetails['format']);
            });
        });


        return $next($request);
    }
}
