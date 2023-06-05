<?php
class ExchangeRateTableGenerator {
    private $dbHost = 'localhost';
    private $dbUser = 'root';
    private $dbPass = 'mck01';
    private $dbName = 'kursywalut';
    
    public function generateTable() {
        $conn = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
        
        if (!$conn) {
            die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
        }
        
        $sql = "SELECT * FROM exchange_rates ORDER BY date DESC";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr><th>Data</th><th>Waluta</th><th>Kurs</th></tr>';
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['date'] . '</td>';
                echo '<td>' . $row['currency'] . '</td>';
                echo '<td>' . $row['rate'] . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
        } else {
            echo 'Brak danych do wyświetlenia.';
        }
        
        mysqli_close($conn);
    }
}

// Przykład użycia
$tableGenerator = new ExchangeRateTableGenerator();
$tableGenerator->generateTable();
?>