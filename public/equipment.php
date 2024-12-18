<?php
require_once __DIR__ . '/../src/models/user_model.php';
session_start();
require_once __DIR__ . '/../src/DBConect.php';
require_once __DIR__ . '/../src/models/equipment_model.php';

if (!isset($_SESSION['user'])) {
    $role = -1;
    
} else {
    $role = $_SESSION['user']->getRole();
    echo "Witaj ";
}

$db = new Database();
$conn = $db->getConnection();

$eq = new Equipment();
$equipmentList = $eq->getAllEquipment();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wybierz Sprzęt</title>
    <link rel="stylesheet" href="css/equipment.css">
</head>
<body>
    <div id="equipment-container">
        <form id="equipmentForm" action="rent.php" method="post">
            <?php foreach ($equipmentList as $equipment): ?>
                <div class="equipment-card">
                    <p><strong>ID:</strong> <?= htmlspecialchars($equipment['E_id']) ?></p>
                    <p><strong>Cena:</strong> <?= htmlspecialchars($equipment['E_price']) ?> PLN</p>
                    <p><strong>Status:</strong> <?= $equipment['E_if_rent'] === '0' ? 'Dostępny' : 'Wypożyczony' ?></p>
                    <?php if ($equipment['E_if_rent'] === 'Dostępny' && $role !== -1): ?>
                        <label>
                            <input type="checkbox" name="equipment_ids[]" value="<?= htmlspecialchars($equipment['E_id']) ?>">
                            Wypożycz
                        </label>
                    <?php else: ?>
                        <button disabled><?= $equipment['E_if_rent'] === '0' ? 'Zaloguj się, aby wypożyczyć' : 'Wypożyczony' ?></button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <input type="submit" value="Wypożycz wybrany sprzęt" <?= $role === -1 ? 'disabled' : '' ?>>
        </form>
        <?php if ($role === -1): ?>
            <p>Aby wypożyczyć sprzęt, proszę się <a href="login.php">zalogować</a>.</p>
        <?php endif; ?>
    </div>

    <script>
    document.getElementById('equipmentForm').addEventListener('submit', function(event) {
        const checkboxes = document.querySelectorAll('input[name="equipment_ids[]"]');
        const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        if (!isAnyChecked) {
            alert('Proszę wybrać przynajmniej jeden element sprzętu do wypożyczenia.');
            event.preventDefault();
        }
    });
    </script>
</body>
</html>
