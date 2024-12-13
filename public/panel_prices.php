<?php
require_once __DIR__ . '/../src/models/user_model.php';
require_once __DIR__ . '/../src/models/admin_model.php';
session_start(); 

$admin = new Admin();
$meseges = [
    'price' => '',
    'service_price' => ''
];
// Sprawdź, czy formularz został przesłany metodą POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['price'])) {
    foreach ($_POST['price'] as $equipmentName => $newPrice) {
        $newPrice = floatval($newPrice); // Upewnij się, że cena jest liczbą zmiennoprzecinkową
        try {
            $admin->editPrice($equipmentName, $newPrice); // Wywołaj metodę aktualizacji ceny w klasie Admin
        } catch (Exception $e) {
            echo "Błąd podczas aktualizacji: " . $e->getMessage();
        }
    }
    $meseges['price'] = "<p class='success' >Ceny zostały pomyślnie zaktualizowane!</p>";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_price'])) {
    foreach ($_POST['service_price'] as $serviceName => $newPrice) {
        $newPrice = floatval($newPrice); // Upewnij się, że cena jest liczbą zmiennoprzecinkową
        try {
            $admin->editServicePriceByName($serviceName, $newPrice); // Wywołaj metodę aktualizacji ceny w klasie Admin
        } catch (Exception $e) {
            echo "Błąd podczas aktualizacji: " . $e->getMessage();
        }
    }
    $meseges['service_price'] =  "<p class='success'>Ceny usług zostały pomyślnie zaktualizowane!</p>";
}
// Pobierz dane do wyświetlenia w formularzu
$price_equipment = $admin->getPrice();
$service_prices = $admin->getServicePrices();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
    <title>Panel Cen</title>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/panel_prices.css">
</head>
<body>
    <?php
        include __DIR__ . '/menu.php';
    ?>
    <main>
        <div class="table">
            <h1>Ceny Sprzętu</h1>
        <form method="post" action="panel_prices.php">
            <table border="1">
                <thead>
                    <tr>
                        <th>Nazwa Sprzętu</th>
                        <th>Cena (PLN)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($price_equipment as $row): ?>
                    <tr>
                        <td><?= $row[0] ?></td>
                        <td>
                            <input type="number" name="price[<?= $row[0] ?>]" value="<?= $row[1] ?>" step="0.01" required>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="bnt">Zapisz zmiany</button>
            <?= $meseges['price'];?>
            </div>
        </form>
        <div class="vertical_line"></div>
        <div class="table">
            <h1>Ceny Serwisu</h1>
        <form method="post" action="panel_prices.php">
            <table border="1">
                <thead>
                    <tr>
                        <th>Nazwa Usługi</th>
                        <th>Cena (PLN)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($service_prices as $row): ?>
                    <tr>
                        <td><?= $row['SP_service'] ?></td>
                        <td>
                            <input type="number" name="service_price[<?= $row['SP_service'] ?>]" value="<?= $row['SP_price'] ?>" step="0.01" required>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="bnt">Zapisz zmiany</button>
            <?= $meseges['service_price'];?>
        </form>
        </div>
    </main>
</body>
</html>
