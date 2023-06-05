<?php
class zapisConvert {
    private $dbConnection;
    
    public function __construct($dbHost, $dbUser, $dbPass, $dbName) {
        $this->dbConnection = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
        
        if ($this->dbConnection->connect_error) {
            die('Błąd połączenia z bazą danych: ' . $this->dbConnection->connect_error);
        }
    }
    
    public function convert($amount, $sourceCurrency, $targetCurrency) {
        $sourceRate = $this->getExchangeRate($sourceCurrency);
        $targetRate = $this->getExchangeRate($targetCurrency);
        
        if ($sourceRate !== null && $targetRate !== null) {
            $convertedAmount = $amount * ($targetRate / $sourceRate);
            
            // Zapisz przewalutowane dane do bazy danych
            $this->saveConversion($amount, $sourceCurrency, $targetCurrency, $convertedAmount);
            
            return $convertedAmount;
        }
        
        return null;
    }
    
    private function getExchangeRate($currency) {
        $query = "SELECT rate FROM exchange_rates WHERE currency = ? ORDER BY date DESC LIMIT 1";
        $statement = $this->dbConnection->prepare($query);
        $statement->bind_param('s', $currency);
        $statement->execute();
        $result = $statement->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['rate'];
        }
        
        return null;
    }
    
    private function saveConversion($amount, $sourceCurrency, $targetCurrency, $convertedAmount) {
        $query = "INSERT INTO conversions (amount, source_currency, target_currency, converted_amount) VALUES (?, ?, ?, ?)";
        $statement = $this->dbConnection->prepare($query);
        $statement->bind_param('dsss', $amount, $sourceCurrency, $targetCurrency, $convertedAmount);
        
        if ($statement->execute()) {
            echo "Zapisano przeliczenie do bazy danych.";
        } else {
            echo "Błąd zapisu do bazy danych: " . $statement->error;
        }
    }
    
    public function __destruct() {
        $this->dbConnection->close();
    }
}

// Przykład użycia
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'mck01';
$dbName = 'kursywalut';

$converter = new zapisConvert($dbHost, $dbUser, $dbPass, $dbName);
$amount = 100;
$sourceCurrency = 'USD';
$targetCurrency = 'EUR';
$convertedAmount = $converter->convert($amount, $sourceCurrency, $targetCurrency);

if ($convertedAmount !== null) {
    echo "Przeliczona kwota: $convertedAmount $targetCurrency";
} else {
    echo 'Błąd przeliczania kwoty walutowej.';
}
?>