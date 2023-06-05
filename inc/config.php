<?php
$servername = "localhost";
$username = "root";
$password = "mck01"; // hasło do bazy danych
$dbname = "kursywalut"; // nazwa nowej bazy danych

// Tworzenie połączenia z serwerem MySQL
$conn = new mysqli($servername, $username, $password);

// Sprawdzanie czy połączenie zostało poprawnie nawiązane
if ($conn->connect_error) {
    die("Nieudane połączenie: " . $conn->connect_error);
}

// Tworzenie nowej bazy danych
$sql = "CREATE DATABASE $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Baza danych $dbname została utworzona pomyślnie<br>";
} else {
    echo "Błąd podczas tworzenia bazy danych: " . $conn->error;
}

// Konfiguracja połączenia z nową bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzanie czy połączenie z nową bazą danych zostało poprawnie skonfigurowane
if ($conn->connect_error) {
    die("Nieudane połączenie z bazą danych: " . $conn->connect_error);
} else {
    echo "Połączenie z bazą danych $dbname zostało pomyślnie skonfigurowane<br>";
}

// Zamykanie połączenia z serwerem MySQL
$conn->close();
?>