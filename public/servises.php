<?php
require_once __DIR__ . '/../src/models/user_model.php';
session_start();
require_once __DIR__ . '/../src/DBConect.php';

$db = new Database();
$conn = $db->getConnection();

// Query to get all service prices
$query = "SELECT SP_service, SP_price FROM service_price";
$result = $conn->query($query);

// Fetch results into an array
$servicePrices = [];
if ($result) {
    $servicePrices = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Prices</title>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/prices.css">
</head>
<body>
    <?php include __DIR__ . '/menu.php'; ?>
    <main>
        <section id="prices">
            <h1>Cennik Serwisu</h1>
            <table>
                <thead>
                    <tr>
                        <th>Usługa</th>
                        <th>Cena (PLN)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicePrices as $service): ?>
                        <tr>
                            <td><?= htmlspecialchars($service['SP_service'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($service['SP_price'], ENT_QUOTES, 'UTF-8') ?> PLN</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p style="text-align: center; margin-top: 20px;">W razie pytań zapraszamy na miejsce, gdzie chętnie udzielimy odpowiedzi na wszelkie pytania!</p>
        </section>
    </main>
</body>
</html>
