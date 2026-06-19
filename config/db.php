<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "bookhub";
$port = 3306;

$localConfig = __DIR__ . "/local.php";
if (file_exists($localConfig)) {
    $settings = require $localConfig;

    if (is_array($settings)) {
        $host = $settings["host"] ?? $host;
        $user = $settings["user"] ?? $user;
        $pass = $settings["pass"] ?? $pass;
        $db   = $settings["db"] ?? $db;
        $port = $settings["port"] ?? $port;
    }
}

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($host, $user, $pass, $db, (int)$port);

if ($conn->connect_error) {
    http_response_code(500);
    die(
        "<h1>Database Connection Failed</h1>" .
        "<p>MySQL rejected the connection for user <strong>" . htmlspecialchars($user) . "</strong>.</p>" .
        "<p>Check that MySQL is running, import <code>database/database.sql</code>, and update <code>config/local.php</code> if your MySQL password or port is different.</p>" .
        "<p>Original error: " . htmlspecialchars($conn->connect_error) . "</p>"
    );
}

$conn->set_charset("utf8mb4");

?>
