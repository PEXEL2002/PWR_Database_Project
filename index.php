<?php
require_once __DIR__ . '/src/config.php';
function createDatabaseBackup() {
    $host = DB_HOST;
    $user = DB_USER;
    $password = DB_PASS;
    $dbName = DB_NAME;
    $backupDir = __DIR__ . '/database/backups';
    if (!file_exists($backupDir)) {
        mkdir($backupDir, 0777, true);
    }
    $date = date('Y-m-d');
    $backupFile = "{$backupDir}/backup_{$date}.sql";
    $command = "mysqldump -h {$host} -u {$user} -p{$password} {$dbName} > \"{$backupFile}\"";
    exec($command);
}
createDatabaseBackup();
header("Location: public/main.php");
exit;
?>
