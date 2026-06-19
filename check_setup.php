<?php

$settings = [
    "host" => "localhost",
    "user" => "root",
    "pass" => "",
    "db" => "bookhub",
    "port" => 3306,
];

$localConfig = __DIR__ . "/config/local.php";
if (file_exists($localConfig)) {
    $localSettings = require $localConfig;

    if (is_array($localSettings)) {
        $settings = array_merge($settings, $localSettings);
    }
}

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli(
    $settings["host"],
    $settings["user"],
    $settings["pass"],
    $settings["db"],
    (int)$settings["port"]
);

$connected = !$conn->connect_error;
$tables = [];

if ($connected) {
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookHub Setup Check</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.5; color: #222; }
        code { background: #f2f2f2; padding: 2px 6px; border-radius: 4px; }
        .ok { color: #087f23; font-weight: bold; }
        .bad { color: #b00020; font-weight: bold; }
        table { border-collapse: collapse; margin-top: 16px; }
        td, th { border: 1px solid #ddd; padding: 8px 12px; text-align: left; }
    </style>
</head>
<body>
    <h1>BookHub Setup Check</h1>
    <p>PHP version: <strong><?php echo htmlspecialchars(PHP_VERSION); ?></strong></p>
    <p>MySQLi extension: <?php echo extension_loaded("mysqli") ? "<span class=\"ok\">Loaded</span>" : "<span class=\"bad\">Missing</span>"; ?></p>
    <p>Using local config: <?php echo file_exists($localConfig) ? "<span class=\"ok\">Yes</span>" : "No, using defaults"; ?></p>

    <table>
        <tr><th>Setting</th><th>Value</th></tr>
        <tr><td>Host</td><td><?php echo htmlspecialchars($settings["host"]); ?></td></tr>
        <tr><td>User</td><td><?php echo htmlspecialchars($settings["user"]); ?></td></tr>
        <tr><td>Password</td><td><?php echo $settings["pass"] === "" ? "(empty)" : "(set)"; ?></td></tr>
        <tr><td>Database</td><td><?php echo htmlspecialchars($settings["db"]); ?></td></tr>
        <tr><td>Port</td><td><?php echo htmlspecialchars((string)$settings["port"]); ?></td></tr>
    </table>

    <?php if ($connected): ?>
        <p class="ok">Database connected successfully.</p>
        <p>Tables found: <?php echo $tables ? htmlspecialchars(implode(", ", $tables)) : "No tables found. Import database/database.sql."; ?></p>
        <p><a href="index.php">Open BookHub</a></p>
    <?php else: ?>
        <p class="bad">Database connection failed.</p>
        <p>Error: <code><?php echo htmlspecialchars($conn->connect_error); ?></code></p>
        <p>Fix: start MySQL, import <code>database/database.sql</code>, then update <code>config/local.php</code> if your username, password, or port is different.</p>
    <?php endif; ?>
</body>
</html>
