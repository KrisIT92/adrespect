<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $sourceCurrency = $_POST['sourceCurrency'];
    $targetCurrency = $_POST['targetCurrency'];
    
    // Tutaj możesz dodać logikę konwersji walutowej
    // np. wykorzystując wcześniej zaprezentowany kod komunikacji z API NBP
    
    // Przykład: Prosta konwersja walutowa - pomnożenie kwoty przez 4
    $convertedAmount = $amount * 4;
    
    echo "Przeliczona kwota: $convertedAmount $targetCurrency";
}
?>
