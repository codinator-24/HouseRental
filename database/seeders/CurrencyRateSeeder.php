<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Config;

class CurrencyRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultUsdToIqdRate = Config::get('currency.exchange_rates.USD_TO_IQD', 1460); // Default to 1460 if not found

        CurrencyRate::updateOrCreate(
            ['rate_name' => 'USD_TO_IQD'],
            ['rate_value' => $defaultUsdToIqdRate]
        );
    }
}
