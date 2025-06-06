<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    /**
     * Switch the active currency and store it in the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function switch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => ['required', 'string', function ($attribute, $value, $fail) {
                $supportedCurrencies = Config::get('currency.currencies', []);
                if (!in_array($value, $supportedCurrencies)) {
                    $fail(ucfirst($attribute).' is not a supported currency.');
                }
            }],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $newCurrency = $request->input('currency');
        Session::put('currency', $newCurrency);

        // Optionally, you can also return the new currency format details
        $currencyConfig = Config::get('currency');
        $newFormat = $currencyConfig['format'][$newCurrency] ?? $currencyConfig['format'][$currencyConfig['default']];

        return response()->json([
            'status' => 'success',
            'message' => 'Currency switched to ' . $newCurrency,
            'newCurrency' => $newCurrency,
            'newFormat' => $newFormat
        ]);
    }
}
