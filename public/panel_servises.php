<?php
require_once __DIR__ . '/../src/models/user_model.php';
require_once __DIR__ . '/../src/models/admin_model.php';
session_start();

$admin = new Admin();

// Inicjalizacja tablicy komunikatów
$messages = [
    'add_service' => null,
    'mark_completed' => null,
];

// Pobranie listy użytkowników i operacji serwisowych
$users = $admin->getUsers();
$operations = $admin->getServiceOperations();
$serviceRecords = $admin->getServiceRecords();

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_service'])) {
        $userId = intval($_POST['user']);
        $operationId = intval($_POST['operation']);

        try {
            $admin->addServiceRecord($userId, $operationId);
            $messages['add_service'] = "Sprzęt został przyjęty do serwisu!";
        } catch (Exception $e) {
            $messages['add_service'] = "Nie udało się przyjąć sprzętu.";
        }
    }

    if (isset($_POST['mark_completed'])) {
        $serviceId = intval($_POST['service_id']);

        try {
            $admin->markServiceAsCompleted($serviceId);
            $messages['mark_completed'] = "Sprzęt został wydany!";
            $serviceRecords = $admin->getServiceRecords(); // Odśwież dane
        } catch (Exception $e) {
            $messages['mark_completed'] = "Nie udało się wydać sprzętu.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Serwisu</title>
    <link rel="stylesheet" href="css/menu.css">
   
    <link rel="stylesheet" href="css/panel_servises.css">
</head>
<body>
<?php include __DIR__ . '/menu.php'; ?>
<main>
    <h1>Panel Serwisu Sprzętu</h1>

    <section>
        <h2>Przyjmij sprzęt do serwisu</h2>
        <?php if ($messages['add_service']): ?>
            <p class='success'><?= htmlspecialchars($messages['add_service']) ?></p>
        <?php endif; ?>
        <form method="post" action="panel_servises.php">
            <div>
                <label for="user">Wybierz użytkownika:</label>
                <select name="user" id="user" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['U_id'] ?>">
                            <?= htmlspecialchars($user['U_name'] . ' ' . $user['U_surname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="operation">Wybierz operację serwisową:</label>
                <select name="operation" id="operation" required>
                    <?php foreach ($operations as $operation): ?>
                        <option value="<?= $operation['SP_id'] ?>">
                            <?= htmlspecialchars($operation['SP_service']) ?> (<?= $operation['SP_price'] ?> PLN)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" name="add_service">Przyjmij sprzęt</button>
        </form>
    </section>
    <section>
        <h2>Wydawanie z serwisu</h2>
        <?php if ($messages['mark_completed']): ?>
            <p class='success'><?= htmlspecialchars($messages['mark_completed']) ?></p>
        <?php endif; ?>
        <table>
            <thead>
            <tr>
                <th>Użytkownik</th>
                <th>Operacja</th>
                <th>Cena</th>
                <th>Data przyjęcia</th>
                <th>Status</th>
                <th>Akcje</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($serviceRecords)): ?>
                <?php foreach ($serviceRecords as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['U_name'] . ' ' . $record['U_surname']) ?></td>
                        <td><?= htmlspecialchars($record['SP_service']) ?></td>
                        <td><?= htmlspecialchars($record['SP_price']) ?> PLN</td>
                        <td><?= htmlspecialchars($record['S_date_in']) ?></td>
                        <td><?= htmlspecialchars($record['S_status']) ?></td>
                        <td>
                            <form method="post" action="panel_servises.php" style="display:inline;">
                                <input type="hidden" name="service_id" value="<?= $record['S_id'] ?>">
                                <button type="submit" name="mark_completed">Wydaj sprzęt</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Brak sprzętu do wydania</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
