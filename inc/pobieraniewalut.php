<?php 
class NBP_API {
    private $apiUrl = 'https://api.nbp.pl/api/exchangerates/rates/';
    
    public function getExchangeRates($table, $code) {
        $url = $this->apiUrl . $table . '/' . $code;
        
        $response = $this->sendRequest($url);
        
        if ($response !== false) {
            $data = json_decode($response, true);
            
            if (isset($data['rates'])) {
                return $data['rates'];
            }
        }
        
        return null;
    }
    
    private function sendRequest($url) {
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        
        return $response;
    }
}

// Przykład użycia
$nbp = new NBP_API();
$rates = $nbp->getExchangeRates('a', 'USD');

if ($rates) {
    foreach ($rates as $rate) {
        echo 'Data: ' . $rate['effectiveDate'] . ', Kurs: ' . $rate['mid'] . '<br>';
    }
} else {
    echo 'Błąd pobierania kursów walut z API NBP.';
}
?>