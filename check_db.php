<?php
$db = new PDO('sqlite:database/database.sqlite');
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
echo "Tables in SQLite database:\n";
foreach ($tables as $t) {
    echo " - $t\n";
}
echo "\nUser count: " . $db->query('SELECT COUNT(*) FROM users')->fetchColumn() . "\n";
echo 'Room type count: ' . $db->query('SELECT COUNT(*) FROM room_types')->fetchColumn() . "\n";
echo 'Room count: ' . $db->query('SELECT COUNT(*) FROM rooms')->fetchColumn() . "\n";
echo 'Guest count: ' . $db->query('SELECT COUNT(*) FROM guests')->fetchColumn() . "\n";
echo 'Booking count: ' . $db->query('SELECT COUNT(*) FROM bookings')->fetchColumn() . "\n";