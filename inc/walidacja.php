class CurrencyConverter {
    private $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function convert($amount, $sourceCurrency, $targetCurrency) {
        // Walidacja danych wejściowych
        $errors = $this->validateInput($amount, $sourceCurrency, $targetCurrency);

        if (!empty($errors)) {
            return ['error' => $errors];
        }

        $sourceRate = $this->getExchangeRate($sourceCurrency);
        $targetRate = $this->getExchangeRate($targetCurrency);

        if ($sourceRate !== null && $targetRate !== null) {
            $convertedAmount = $amount * ($targetRate / $sourceRate);
            $this->saveConversion($amount, $sourceCurrency, $targetCurrency, $convertedAmount);

            return ['converted_amount' => $convertedAmount];
        }

        return ['error' => 'Nie można przeliczyć kwoty.'];
    }

    private function validateInput($amount, $sourceCurrency, $targetCurrency) {
        $errors = [];

        // Walidacja kwoty
        if (!is_numeric($amount) || $amount <= 0) {
            $errors[] = 'Nieprawidłowa kwota.';
        }

        // Walidacja walut
        if (empty($sourceCurrency) || empty($targetCurrency)) {
            $errors[] = 'Nieprawidłowa waluta.';
        }

        return $errors;
    }

    private function getExchangeRate($currency) {
        // Implementacja pobierania kursu z bazy danych
    }

    private function saveConversion($amount, $sourceCurrency, $targetCurrency, $convertedAmount) {
        // Implementacja zapisu przeliczenia do bazy danych
    }
}

// Przykład użycia
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'your_database_name';

$dbConnection = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($dbConnection->connect_error) {
    die('Błąd połączenia z bazą danych: ' . $dbConnection->connect_error);
}

$currencyConverter = new CurrencyConverter($dbConnection);

// Przykładowe dane wejściowe
$amount = 100;
$sourceCurrency = 'USD';
$targetCurrency = 'EUR';

$result = $currencyConverter->convert($amount, $sourceCurrency, $targetCurrency);

if (isset($result['error'])) {
    echo 'Wystąpił błąd: ' . $result['error'];
} else {
    echo 'Przeliczona kwota: ' . $result['converted_amount'];
}

$dbConnection->close();
