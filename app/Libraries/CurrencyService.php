<?php

namespace App\Libraries;

class CurrencyService
{
    private $apiUrl = 'https://api.exchangerate-api.com/v4/latest/';
    private $baseCurrency = 'PHP';
    private $cacheFile;
    private $cacheExpiry = 3600; // 1 hour
    
    public function __construct()
    {
        $this->cacheFile = WRITEPATH . 'cache/exchange_rates.json';
    }
    
    /**
     * Convert amount from one currency to another
     * 
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float|null
     */
    public function convert($amount, $fromCurrency, $toCurrency = 'PHP')
    {
        // If same currency, no conversion needed
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }
        
        $rate = $this->getExchangeRate($fromCurrency, $toCurrency);
        
        if ($rate === null) {
            // Fallback to 1:1 if API fails
            log_message('error', "Currency conversion failed for {$fromCurrency} to {$toCurrency}. Using 1:1 rate.");
            return $amount;
        }
        
        return round($amount * $rate, 2);
    }
    
    /**
     * Get exchange rate from one currency to another
     * 
     * @param string $from
     * @param string $to
     * @return float|null
     */
    public function getExchangeRate($from, $to)
    {
        $rates = $this->fetchRates($from);
        
        if (isset($rates[$to])) {
            return $rates[$to];
        }
        
        return null;
    }
    
    /**
     * Fetch exchange rates from API with caching
     * 
     * @param string $baseCurrency
     * @return array
     */
    private function fetchRates($baseCurrency)
    {
        // Check cache first
        $cachedRates = $this->getCachedRates($baseCurrency);
        if ($cachedRates !== null) {
            return $cachedRates;
        }
        
        // Fetch from API
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->get($this->apiUrl . $baseCurrency, [
                'timeout' => 5,
                'http_errors' => false,
            ]);
            
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                
                if (isset($data['rates'])) {
                    $this->cacheRates($baseCurrency, $data['rates']);
                    return $data['rates'];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Currency API error: ' . $e->getMessage());
        }
        
        // Return fallback rates if API fails
        return $this->getFallbackRates($baseCurrency);
    }
    
    /**
     * Get cached exchange rates
     * 
     * @param string $baseCurrency
     * @return array|null
     */
    private function getCachedRates($baseCurrency)
    {
        if (!file_exists($this->cacheFile)) {
            return null;
        }
        
        $cache = json_decode(file_get_contents($this->cacheFile), true);
        
        if (!isset($cache[$baseCurrency])) {
            return null;
        }
        
        $cacheData = $cache[$baseCurrency];
        
        // Check if cache is expired
        if (time() - $cacheData['timestamp'] > $this->cacheExpiry) {
            return null;
        }
        
        return $cacheData['rates'];
    }
    
    /**
     * Cache exchange rates
     * 
     * @param string $baseCurrency
     * @param array $rates
     */
    private function cacheRates($baseCurrency, $rates)
    {
        $cache = [];
        
        if (file_exists($this->cacheFile)) {
            $cache = json_decode(file_get_contents($this->cacheFile), true);
        }
        
        $cache[$baseCurrency] = [
            'rates' => $rates,
            'timestamp' => time(),
        ];
        
        // Ensure cache directory exists
        $cacheDir = dirname($this->cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        
        file_put_contents($this->cacheFile, json_encode($cache));
    }
    
    /**
     * Fallback exchange rates if API fails
     * 
     * @param string $baseCurrency
     * @return array
     */
    private function getFallbackRates($baseCurrency)
    {
        // Static fallback rates (approximate as of 2024)
        $fallbackRates = [
            'PHP' => [
                'PHP' => 1,
                'USD' => 0.018,
                'EUR' => 0.016,
            ],
            'USD' => [
                'PHP' => 56.00,
                'USD' => 1,
                'EUR' => 0.92,
            ],
            'EUR' => [
                'PHP' => 61.00,
                'USD' => 1.09,
                'EUR' => 1,
            ],
        ];
        
        return $fallbackRates[$baseCurrency] ?? ['PHP' => 1, 'USD' => 1, 'EUR' => 1];
    }
    
    /**
     * Get list of supported currencies
     * 
     * @return array
     */
    public function getSupportedCurrencies()
    {
        return [
            'PHP' => 'Philippine Peso',
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
        ];
    }
}
