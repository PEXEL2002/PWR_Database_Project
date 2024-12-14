<?php
require_once __DIR__ . '/../src/models/admin_model.php';
session_start();

$admin = new Admin();

// Pobranie historii transakcji
$transactions = $admin->getAllTransactions();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Użytkowników</title>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/panel_servises.css">
</head>
<body>
<?php include __DIR__ . '/menu.php'; ?>
<main>
    <h1>Historia Transakcji Użytkowników</h1>

    <?php if (isset($_SESSION['messages'])): ?>
        <div class="messages">
            <?php foreach ($_SESSION['messages'] as $message): ?>
                <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['messages']); ?>
        </div>
    <?php endif; ?>

    <section>
        <h2>Lista Transakcji</h2>
        <table>
            <thead>
                <tr>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>E-mail</th>
                    <th>Data Wypożyczenia</th>
                    <th>Data Zwrotu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaction['U_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($transaction['U_surname'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($transaction['U_mail'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($transaction['R_date_out'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($transaction['R_date_return'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($transaction['R_status'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
