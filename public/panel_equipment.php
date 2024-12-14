<?php
require_once __DIR__ . '/../src/models/admin_model.php';
session_start();

$admin = new Admin();

// Obsługa dodawania sprzętu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_equipment'])) {
    $E_producer = $_POST['E_producer'];
    $E_category = $_POST['E_category'];
    $E_size = $_POST['E_size'];
    $E_price = $admin->getEquipmentPrice($E_category);
    $E_if_rent = $_POST['E_if_rent'];

    $target_dir = __DIR__ . "/assets/equipmentPhoto/";
    $file_name = basename($_FILES["E_photo"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["E_photo"]["tmp_name"], $target_file)) {
        $admin->addEquipment($E_producer, $E_category, $E_size, $E_price, $E_if_rent, $file_name);
        $_SESSION['messages'][] = "Sprzęt został dodany.";
    } else {
        $_SESSION['messages'][] = "Błąd przesyłania pliku dla sprzętu.";
    }
}

// Pobranie wszystkich rekordów sprzętu
$equipmentList = $admin->getEquipmentByStatus('all');
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Sprzętu</title>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/panel_servises.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const equipmentTableBody = document.getElementById('equipmentTableBody');
            const filterSelect = document.getElementById('equipmentStatus');

            // Filtrowanie sprzętu za pomocą JS
            filterSelect.addEventListener('change', function() {
                const selectedStatus = filterSelect.value;
                const rows = equipmentTableBody.querySelectorAll('tr.equipment-row');

                rows.forEach(row => {
                    const statusCell = row.querySelector('.status-cell').textContent.trim();
                    row.style.display = (selectedStatus === 'all' || statusCell === selectedStatus) 
                        ? '' 
                        : 'none';
                });
            });
        });
    </script>
</head>
<body>
<?php include __DIR__ . '/menu.php'; ?>
<main>
    <h1>Panel Zarządzania Sprzętem</h1>

    <?php if (isset($_SESSION['messages'])): ?>
        <div class="messages">
            <?php foreach ($_SESSION['messages'] as $message): ?>
                <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['messages']); ?>
        </div>
    <?php endif; ?>

    <section>
        <h2>Lista Sprzętu</h2>
        <label for="equipmentStatus">Wybierz status sprzętu:</label>
        <select id="equipmentStatus">
            <option value="all">Wszystko</option>
            <option value="Dostępny">Na Stanie</option>
            <option value="Zarezerwowany">Zarezerwowany</option>
            <option value="Wynajęty">Wypożyczony</option>
        </select>

        <table>
            <thead>
                <tr>
                    <th>Producent</th>
                    <th>Kategoria</th>
                    <th>Rozmiar</th>
                    <th>Cena (PLN)</th>
                    <th>Status</th>
                    <th>Zdjęcie</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody id="equipmentTableBody">
                <!-- Formularz dodawania sprzętu -->
                <form method="post" action="panel_equipment.php" enctype="multipart/form-data">
                    <tr>
                        <td>
                            <select name="E_producer" required>
                                <option value="" disabled selected>Wybierz producenta</option>
                                <?php foreach ($admin->getProducers() as $producer): ?>
                                    <option value="<?= $producer['B_id'] ?>">
                                        <?= htmlspecialchars($producer['B_name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="E_category" required>
                                <option value="" disabled selected>Wybierz kategorię</option>
                                <?php foreach ($admin->getCategories() as $category): ?>
                                    <option value="<?= $category['C_id'] ?>">
                                        <?= htmlspecialchars($category['C_Name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="E_size" step="0.01" required></td>
                        <td id="E_price">Automatyczna</td>
                        <td>
                            <select name="E_if_rent" required>
                                <option value="Dostępny">Dostępny</option>
                                <option value="Wynajęty">Wynajęty</option>
                                <option value="Zarezerwowany">Zarezerwowany</option>
                            </select>
                        </td>
                        <td><input type="file" name="E_photo" accept="image/*" required></td>
                        <td><button type="submit" name="add_equipment">Dodaj sprzęt</button></td>
                    </tr>
                </form>

                <!-- Wyświetlanie istniejącego sprzętu -->
                <?php foreach ($equipmentList as $equipment): ?>
                <tr class="equipment-row">
                    <td><?= htmlspecialchars($equipment['B_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($equipment['C_Name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($equipment['E_size'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($equipment['E_price'], ENT_QUOTES, 'UTF-8') ?> PLN</td>
                    <td class="status-cell"><?= htmlspecialchars($equipment['E_if_rent'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <img src="assets/equipmentPhoto/<?= htmlspecialchars($equipment['E_photo'], ENT_QUOTES, 'UTF-8') ?>" 
                             alt="Zdjęcie sprzętu" width="100">
                    </td>
                    <td>-</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
