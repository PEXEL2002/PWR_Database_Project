<?php
require_once __DIR__ . '/../src/models/user_model.php';
require_once __DIR__ . '/../src/models/equipment_model.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['equipment_ids'])) {
    // Ensure the user is logged in
    if (!isset($_SESSION['user'])) {
        echo "Musisz być zalogowany, aby wypożyczyć sprzęt.";
        exit;
    }

    $equipmentIds = array_map('intval', $_POST['equipment_ids']); // Sanitize input
    echo "Wybrano sprzęt o ID: " . implode(', ', $equipmentIds) . "<br>";

    $eq = new Equipment();
    $totalPrice = $eq->calculateTotalPrice($equipmentIds);
    echo "Całkowita cena: $totalPrice PLN<br>";

    $userId = $_SESSION['user']->getId();
    $date = date('Y-m-d');

    // Create a new rental record
    $rentId = $eq->createRental($userId, $totalPrice, $date);

    // Process each selected equipment item
    foreach ($equipmentIds as $equipmentId) {
        // Add equipment to the rental order
        $eq->addEquipmentToOrder($rentId, $equipmentId);

        // Attempt to rent the equipment
        if ($eq->rentEquipment($equipmentId)) {
            echo "Sprzęt o ID $equipmentId został wypożyczony.<br>";
        } else {
            echo "Sprzęt o ID $equipmentId jest niedostępny lub wystąpił błąd.<br>";
        }
    }
} else {
    echo "Nie wybrano żadnego sprzętu do wypożyczenia.";
}
?>
