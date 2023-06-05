<?php
class walutyinfo {
    private $dbConnection;
    
    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }
    
    public function getExchangeRatesFromDatabase($limit = 10) {
        $query = "SELECT * FROM exchange_rates ORDER BY date DESC LIMIT ?";
        $statement = $this->dbConnection->prepare($query);
        $statement->bind_param('i', $limit);
        $statement->execute();
        $result = $statement->get_result();
        
        $exchangeRates = [];
        
        while ($row = $result->fetch_assoc()) {
            $exchangeRate = [
                'currency' => $row['currency'],
                'rate' => $row['rate'],
                'date' => $row['date']
            ];
            
            $exchangeRates[] = $exchangeRate;
        }
        
        return $exchangeRates;
    }
}

// Przykład użycia
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'mck01';
$dbName = 'kursywalut';

$dbConnection = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($dbConnection->connect_error) {
    die('Błąd połączenia z bazą danych: ' . $dbConnection->connect_error);
}

$walutyInfo = new walutyinfo($dbConnection);
$exchangeRates = $walutyInfo->getExchangeRatesFromDatabase();

if (!empty($exchangeRates)) {
    echo '<h2>Ostatnie kursy walut:</h2>';
    
    foreach ($exchangeRates as $exchangeRate) {
        $currency = $exchangeRate['currency'];
        $rate = $exchangeRate['rate'];
        $date = $exchangeRate['date'];
        
        echo "Waluta: $currency, Kurs: $rate, Data: $date <br>";
    }
} else {
    echo 'Brak dostępnych kursów walut.';
}

$dbConnection->close();
?>