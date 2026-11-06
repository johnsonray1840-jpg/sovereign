<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LozandServices
{
    /**
     * This is a custom unified API for market data, based on live market data 
     * aggregated by our custom API service.
     * 
     * @var string
     */
    public $base_url;
    public $api_key;

    public function __construct()
    {
        $this->base_url = config('services.binso.base_url');
        $this->api_key = safeDecrypt(config('services.binso.api_key'));
    }

    /**
     * Get market stocks data.
     *
     * @return array
     */
    public function marketStocks()
    {
        if (Cache::has('market_stocks')) {
            return Cache::get('market_stocks');
        }

        try {
            $response = Http::timeout(3)->withHeaders([
                'x-api-key' => $this->api_key,
            ])->get($this->base_url . '/stocks');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put('market_stocks', $data, now()->addMinutes(10));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }


    /**
     * Get ticker information.
     *
     * @param string $ticker
     * @return array
     */
    public function ticker($ticker)
    {
        $cacheKey = 'ticker_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(3)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/stocks/' . $ticker);

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addMinutes(10));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }



    /**
     * Get market ETFs data.
     *
     * @return array
     */
    public function marketEtfs()
    {
        if (Cache::has('market_etfs')) {
            return Cache::get('market_etfs');
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/etfs');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put('market_etfs', $data, now()->addMinutes(2));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }



    /**
     * Get ETF ticker information.
     *
     * @param string $ticker
     * @return array
     */
    public function etfTicker($ticker)
    {
        $cacheKey = 'ticker_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/etfs/' . $ticker);

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addMinutes(5));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }


    /**
     * Get bonds data.
     *
     * @return array
     */
    public function bonds()
    {
        if (Cache::has('bonds')) {
            return Cache::get('bonds');
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/bonds');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put('bonds', $data, now()->addMinutes(2));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }


    /**
     * Get a single bond by "cusip".
     *
     * @param string $cusip
     * @return array
     */
    public function bond($cusip)
    {
        $cacheKey = 'bond_' . $cusip;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/bonds/' . $cusip);

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addMinutes(5));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    /**
     * Fetch future tickers data.
     *
     * @return array
     */
    public function futureTickers()
    {
        $cacheKey = 'future_tickers';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/futures');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                //cache for 6 seconds
                Cache::put($cacheKey, $data, now()->addSeconds(6));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    /**
     * Get single future ticker data.
     *
     * @param string $ticker
     * @return array
     */
    public function futureTicker($ticker)
    {
        $cacheKey = 'future_ticker_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(3)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/futures/' . $ticker);

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addMinutes(2));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }


    /**
     * Get mutual funds data.
     *
     * @return array
     */
    public function mutualFunds()
    {
        $cacheKey = 'mutual_funds';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/mutual-funds');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addMinutes(5));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }


    /**
     * Get futures order book data.
     *
     * @param string $ticker
     * @return array
     */
    public function futuresOrderBook($ticker)
    {
        $cacheKey = 'futures_order_book_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/futures/' . $ticker . '/order-book');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addSeconds(6));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    /**
     * Get futures recent trades data.
     *
     * @param string $ticker
     * @return array
     */
    public function futuresRecentTrades($ticker)
    {
        $cacheKey = 'futures_recent_trades_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/futures/' . $ticker . '/trades');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addSeconds(6));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }


    /**
     * Get margins data.
     *
     * @return array
     */
    public function margins()
    {
        $cacheKey = 'margins';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/margins');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addMinutes(5));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    /**
     * Get margin data.
     *
     * @param string $ticker
     * @return array
     */
    public function margin($ticker)
    {
        $cacheKey = 'margin_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/margins/' . $ticker);

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addMinutes(5));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    /**
     * Get margin order book data.
     *
     * @param string $ticker
     * @return array
     */
    public function marginOrderBook($ticker)
    {
        $cacheKey = 'margin_order_book_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/margins/' . $ticker . '/order-book');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addSeconds(6));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    /**
     * Get margin recent trades data.
     *
     * @param string $ticker
     * @return array
     */
    public function marginRecentTrades($ticker)
    {
        $cacheKey = 'margin_recent_trades_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/margins/' . $ticker . '/trades');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addSeconds(6));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }


    /**
     * Get Forex tickers
     * @return array
     */
    public function forexTickers()
    {
        $cacheKey = 'forex_tickers';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/forex/tickers');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addMinutes(5));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    /**
     * Get ticker
     * @param string $ticker
     * @return array
     */
    public function forexTicker($ticker)
    {
        $ticker = str_replace('/', '_', $ticker);
        $cacheKey = 'forex_ticker_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/forex/tickers/' . $ticker);

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                Cache::put($cacheKey, $data, now()->addMinutes(5));
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }



    /**
     * Get IP
     * @return array
     */
    public function getIp()
    {

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->api_key,
                ])
                ->get($this->base_url . '/ip');

            if ($response->successful()) {
                $data = [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'code' => $response->status()
                ];
                return $data;
            }

            $error_message = $response->json()['message']
                ?? 'Request failed with status: ' . $response->status();

            Log::error($response->body());

            return [
                'status' => 'error',
                'message' => $error_message,
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }



}