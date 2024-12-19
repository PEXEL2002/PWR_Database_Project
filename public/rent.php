<?php
require_once __DIR__ . '/../src/models/user_model.php';
require_once __DIR__ . '/../src/models/equipment_model.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['equipment_ids'])) {
    if (!isset($_SESSION['user'])) {
        #echo "Musisz być zalogowany, aby wypożyczyć sprzęt.";
        exit;
    }

    $equipmentIds = array_map('intval', $_POST['equipment_ids']);

    $eq = new Equipment();
    $totalPrice = $eq->calculateTotalPrice($equipmentIds);
    #echo "Całkowita cena: $totalPrice PLN<br>";

    $userId = $_SESSION['user']->getId();
    $date = date('Y-m-d');
    $rentId = $eq->createRental($userId, $totalPrice, $date);

    foreach ($equipmentIds as $equipmentId) {
        $eq->addEquipmentToOrder($rentId, $equipmentId);
        $eq->rentEquipment($equipmentId);
    }
} else {
    #echo "Nie wybrano żadnego sprzętu do wypożyczenia.";
}

header('Location: userInfo.php');
?>
