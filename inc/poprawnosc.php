<?php
class DbConnection {
    private $host;
    private $user;
    private $password;
    private $database;
    private $connection;

    public function __construct($host, $user, $password, $database) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die('Błąd połączenia z bazą danych: ' . $this->connection->connect_error);
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}

class CurrencyConverter {
    private $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function convert($amount, $sourceCurrency, $targetCurrency) {
        $sourceRate = $this->getExchangeRate($sourceCurrency);
        $targetRate = $this->getExchangeRate($targetCurrency);

        if ($sourceRate !== null && $targetRate !== null) {
            $convertedAmount = $amount * ($targetRate / $sourceRate);
            $this->saveConversion($amount, $sourceCurrency, $targetCurrency, $convertedAmount);

            return $convertedAmount;
        }

        return null;
    }

    private function getExchangeRate($currency) {
        $connection = $this->dbConnection->getConnection();
        $query = "SELECT rate FROM exchange_rates WHERE currency = ? ORDER BY date DESC LIMIT 1";
        $statement = $connection->prepare($query);
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
        $connection = $this->dbConnection->getConnection();
        $query = "INSERT INTO conversions (amount, source_currency, target_currency, converted_amount) VALUES (?, ?, ?, ?)";
        $statement = $connection->prepare($query);
        $statement->bind_param('dsss', $amount, $sourceCurrency, $targetCurrency, $convertedAmount);

        if ($statement->execute()) {
            echo "Zapisano przeliczenie do bazy danych.";
        } else {
            echo "Błąd zapisu do bazy danych: " . $statement->error;
        }
    }
}

class ConversionHistory {
    private $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function getConversionHistory($limit = 10) {
        $connection = $this->dbConnection->getConnection();
        $query = "SELECT * FROM conversions ORDER BY conversion_id DESC LIMIT ?";
        $statement = $connection->prepare($query);
        $statement->bind_param('i', $limit);
        $statement->execute();
        $result = $statement->get_result();
        
        $conversions = [];
        
        while ($row = $result->fetch_assoc()) {
            $conversion = [
                'amount' => $row['amount'],
                'source_currency' => $row['source_currency'],
                'target_currency' => $row['target_currency'],
                'converted_amount' => $row['converted_amount']
            ];
            
            $conversions[] = $conversion;
        }
        
        return $conversions;
    }
}
?>