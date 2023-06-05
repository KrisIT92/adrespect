<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Przelicznik walut</title>
</head>
<body>
    <?php
    include("inc/genwalut.php");
    require("inc/config.php"); // plik konfiguracyjny zadania
    include("inc/pobieraniewalut.php");
    require("inc/zapisDanych.php") 

    ?>
    <div class="container">
        <h1>Kalkulator walutowy</h1>
        
        <form method="POST" action="inc/convert.php">
            <div class="mb-3">
                <label for="amount" class="form-label">Kwota:</label>
                <input type="number" step="any" class="form-control" id="amount" name="amount" required>
            </div>
            
            <div class="mb-3">
                <label for="sourceCurrency" class="form-label">Waluta źródłowa:</label>
                <select class="form-select" id="sourceCurrency" name="sourceCurrency" required>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                  
                </select>
            </div>
            
            <div class="mb-3">
                <label for="targetCurrency" class="form-label">Waluta docelowa:</label>
                <select class="form-select" id="targetCurrency" name="targetCurrency" required>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                 
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Konwertuj</button>
        </form>
    </div>
    

   </body>
</html>
