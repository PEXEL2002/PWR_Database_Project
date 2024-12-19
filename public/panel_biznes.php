<?php
require_once __DIR__ . '/../src/models/admin_model.php';
session_start();

$admin = new Admin();

// Obsługa dodawania firmy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_business'])) {
    $B_name = $_POST['B_name'];
    $B_contact = $_POST['B_contact'];
    $B_nip = $_POST['B_nip'];

    // Obsługa przesyłania pliku
    $target_dir = __DIR__ . "/assets/businessPhoto/";
    $file_name = basename($_FILES["B_photo"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["B_photo"]["tmp_name"], $target_file)) {
        $admin->addBusiness($B_name, $B_contact, $B_nip, $file_name);
        $_SESSION['messages'][] = "Firma $B_name została dodana.";
    } else {
        $_SESSION['messages'][] = "Błąd przesyłania pliku dla firmy $B_name.";
    }
}

// Obsługa aktualizacji firmy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_business'])) {
    $B_id = $_POST['B_id'];
    $B_name = $_POST['B_name'];
    $B_contact = $_POST['B_contact'];
    $B_nip = $_POST['B_nip'];

    $admin->editBusiness($B_id, $B_name, $B_contact, $B_nip);
    $_SESSION['messages'][] = "Zmiany zostały zapisane.";
}

// Obsługa usuwania firmy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_business'])) {
    $B_id = $_POST['B_id'];
    $admin->deleteBusiness($B_id);
    $_SESSION['messages'][] = "Firma została usunięta.";
}

$businessList = $admin->getAllBusinesses();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Firm</title>
    <link rel="icon" type="image/x-icon" href="assets/logo.ico">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/panel_servises.css">
</head>
<body>
<?php include __DIR__ . '/menu.php'; ?>
<main>
    <h1>Panel Zarządzania Firmami</h1>

    <?php if (isset($_SESSION['messages'])): ?>
        <div class="messages">
            <?php foreach ($_SESSION['messages'] as $message): ?>
                <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['messages']); ?>
        </div>
    <?php endif; ?>

    <section>
        <h2>Lista Firm</h2>
        <table>
            <thead>
                <tr>
                    <th>Nazwa Firmy</th>
                    <th>Kontakt</th>
                    <th>NIP</th>
                    <th>Zdjęcie</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dodaj nową firmę jako pierwszy rekord -->
                <form method="post" action="panel_biznes.php" enctype="multipart/form-data">
                    <tr>
                        <td><input type="text" name="B_name" placeholder="Nowa firma" required></td>
                        <td><input type="text" name="B_contact" placeholder="Kontakt" required></td>
                        <td><input type="text" name="B_nip" placeholder="NIP" required></td>
                        <td><input type="file" name="B_photo" accept="image/*" required></td>
                        <td><button type="submit" name="add_business">Dodaj firmę</button></td>
                    </tr>
                </form>

                <!-- Wyświetl istniejące firmy -->
                <?php foreach ($businessList as $business): ?>
                    <form method="post" action="panel_biznes.php">
                        <tr>
                            <td>
                                <input type="hidden" name="B_id" value="<?= $business['B_id'] ?>">
                                <input type="text" name="B_name" value="<?= htmlspecialchars($business['B_name'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </td>
                            <td>
                                <input type="text" name="B_contact" value="<?= htmlspecialchars($business['B_contact'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </td>
                            <td>
                                <input type="text" name="B_nip" value="<?= htmlspecialchars($business['B_NIP'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </td>
                            <td>
                                <?php if (!empty($business['B_photo'])): ?>
                                    <img src="assets/businessPhoto/<?= htmlspecialchars($business['B_photo'], ENT_QUOTES, 'UTF-8') ?>" alt="Zdjęcie firmy" width="100">
                                <?php else: ?>
                                    Brak zdjęcia
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="submit" name="update_business">Zapisz zmiany</button>
                                <button type="submit" name="delete_business" value="<?= $business['B_id'] ?>" onclick="return confirm('Czy na pewno chcesz usunąć tę firmę?')">Usuń</button>
                            </td>
                        </tr>
                    </form>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
