<?php
class NBP_API {
    private $apiUrl = 'https://api.nbp.pl/api/exchangerates/rates/';
    private $dbHost = 'localhost';
    private $dbUser = 'root';
    private $dbPass = 'mck01';
    private $dbName = 'kursywalut';
    
    public function getExchangeRates($table, $code) {
        $url = $this->apiUrl . $table . '/' . $code;
        
        $response = $this->sendRequest($url);
        
        if ($response !== false) {
            $data = json_decode($response, true);
            
            if (isset($data['rates'])) {
                $this->saveExchangeRates($data['rates']);
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
    
    private function saveExchangeRates($rates) {
        $conn = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
        
        if (!$conn) {
            die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
        }
        
        foreach ($rates as $rate) {
            $date = $rate['effectiveDate'];
            $currency = $rate['currency'];
            $mid = $rate['mid'];
            
            $sql = "INSERT INTO exchange_rates (date, currency, rate) VALUES ('$date', '$currency', '$mid')";
            
            if (mysqli_query($conn, $sql)) {
                echo "Pomyślnie zapisano kurs dla waluty $currency na dzień $date.<br>";
            } else {
                echo "Błąd podczas zapisywania kursu dla waluty $currency na dzień $date: " . mysqli_error($conn) . "<br>";
            }
        }
        
        mysqli_close($conn);
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