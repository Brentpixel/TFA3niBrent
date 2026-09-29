<?php
// Quick connection test — run with: php test_db.php
$tests = [
    'localhost'  => new mysqli('localhost',  'root', '', 'tasks_today', 3306),
    '127.0.0.1'  => new mysqli('127.0.0.1',  'root', '', 'tasks_today', 3306),
];

foreach ($tests as $host => $conn) {
    if ($conn->connect_error) {
        echo "FAIL  $host : " . $conn->connect_error . PHP_EOL;
    } else {
        echo "OK    $host" . PHP_EOL;
        $conn->close();
    }
}
