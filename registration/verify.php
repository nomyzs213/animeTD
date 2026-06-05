<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status weryfikacji</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        body {
            background-color: #F4EAE1; /* Jasny pastelowy beż */
            color: #563F2E; /* Głęboki, ciepły brąz do tekstów */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .status-container {
            background: #FAF6F0; /* Kremowe wnętrze karty */
            padding: 45px 35px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(140, 98, 57, 0.08);
            text-align: center;
            max-width: 460px;
            width: 100%;
            border: 1px solid #E6D5C3;
            border-top: 6px solid #A3B899; /* Przygaszona pastelowa zieleń */
        }
        .status-container.error {
            border-top-color: #E29587; /* Przygaszony pastelowy czerwony/koral */
        }
        .icon {
            font-size: 55px;
            margin-bottom: 20px;
            display: inline-block;
        }
        h2 {
            font-size: 24px;
            color: #8C6239;
            margin-bottom: 15px;
            font-weight: 700;
        }
        p {
            color: #7A6855;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 35px;
        }
        .btn {
            display: inline-block;
            background-color: #8C6239; /* Główny akcent brązu */
            color: #FAF6F0;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 25px; /* Zaokrąglone w stylu gry clicker */
            font-weight: 600;
            font-size: 15px;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 4px 10px rgba(140, 98, 57, 0.15);
        }
        .btn:hover {
            background-color: #734F2D;
            transform: translateY(-1px);
        }
        .error-box {
            background: #F3EAE1;
            color: #C26D5C;
            padding: 12px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 13px;
            margin-top: 20px;
            text-align: left;
            word-break: break-all;
            border: 1px solid #E29587;
        }
    </style>
</head>
<body>

    <?php if ($_GET['status'] === 'success'): ?>
        <div class="status-container">
            <div class="icon">✉️</div>
            <h2>Mail został wysłany!</h2>
            <p>Sprawdź swoją skrzynkę (oraz folder SPAM). Wysłaliśmy do Ciebie wiadomość z bezpiecznym odnośnikiem aktywacyjnym.</p>
            <p>Możesz zamkąć to okno</p>
        </div>
    <?php else: ?>
        <div class="status-container error">
            <div class="icon">📭</div>
            <h2>Coś poszło nie tak...</h2>
            <p>Nie udało się dostarczyć wiadomości weryfikacyjnej. Spróbuj wykonać operację ponownie za chwilę.</p>
            <a href="verify.php" class="btn">Spróbuj ponownie</a>
        </div>
    <?php endif; ?>

</body>
</html>