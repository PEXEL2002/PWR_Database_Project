<?php
require_once __DIR__ . '/../src/models/admin_model.php';
require_once __DIR__ . '/../src/models/equipment_model.php';
require_once __DIR__ . '/../src/DBConect.php';
session_start();

// Inicjalizacja połączenia z bazą danych
$db = new Database();
$conn = $db->getConnection();

// Inicjalizacja obiektów Admin i Equipment
$admin = new Admin($conn);
$equipment = new Equipment($conn);

// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['issue_rent_id'])) {
        $rentId = intval($_POST['issue_rent_id']);
        $result = $admin->issueEquipment($rentId);
        $message = $result['message'];
    } elseif (isset($_POST['return_rent_id'])) {
        $rentId = intval($_POST['return_rent_id']);
        $result = $admin->acceptReturn($rentId);
        $message = $result['message'];
        $totalCost = $result['total_cost'] ?? null;
    }
}

// Pobranie historii transakcji
$transactions = $admin->getAllTransactions();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora</title>
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/panel_servises.css">
</head>
<body>
<?php include __DIR__ . '/menu.php'; ?>
<main>
    <h1>Panel Administratora</h1>

    <?php if (isset($message)): ?>
        <div class="messages">
            <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
            <?php if (isset($totalCost)): ?>
                <p>Całkowity koszt: <?= htmlspecialchars($totalCost, ENT_QUOTES, 'UTF-8') ?> PLN</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <section>
        <h2>Lista Transakcji</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Wypożyczenia</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>E-mail</th>
                    <th>Data Wypożyczenia</th>
                    <th>Data Zwrotu</th>
                    <th>Status Sprzętu</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaction['R_id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($transaction['U_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($transaction['U_surname'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($transaction['U_mail'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($transaction['R_date_rental'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($transaction['R_date_submission'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?php
                            // Pobierz status sprzętu dla danego wypożyczenia
                            $equipmentStatus = $admin->getEquipmentStatusByRentId($transaction['R_id']);
                            echo htmlspecialchars($equipmentStatus, ENT_QUOTES, 'UTF-8');
                            ?>
                        </td>
                        <td>
                            <?php if ($equipmentStatus === 'Zarezerwowany'): ?>
                                <!-- Formularz do wydania sprzętu -->
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="issue_rent_id" value="<?= htmlspecialchars($transaction['R_id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit">Wydaj sprzęt</button>
                                </form>
                            <?php elseif ($equipmentStatus === 'Wynajęty'): ?>
                                <!-- Formularz do przyjęcia zwrotu -->
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="return_rent_id" value="<?= htmlspecialchars($transaction['R_id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit">Przyjmij zwrot</button>
                                </form>
                            <?php else: ?>
                                Brak dostępnych akcji
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
