<?php
/**
 * Detailed Connection Test
 * This will show the exact error
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><title>Connection Test</title>";
echo "<style>body{font-family:monospace;padding:20px;} pre{background:#f5f5f5;padding:10px;}</style>";
echo "</head><body><h1>Database Connection Test</h1>";

// Show what values are being used
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'juh7hc';
$username = getenv('DB_USER') ?: 'juh7hc';
$password = getenv('DB_PASSWORD') ?: '';

echo "<h2>Connection Parameters:</h2>";
echo "<pre>";
echo "Host: $host\n";
echo "Database: $dbname\n";
echo "Username: $username\n";
echo "Password: " . (empty($password) ? '(empty)' : '(set)') . "\n";
echo "DSN: pgsql:host=$host;dbname=$dbname\n";
echo "</pre>";

// Try different connection methods
echo "<h2>Testing Connections:</h2>";

// Test 1: Default settings
echo "<h3>Test 1: Default connection</h3>";
try {
    $dsn = "pgsql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>[OK] Connected successfully!</p>";
    
    // Test query
    $stmt = $pdo->query("SELECT version()");
    $version = $stmt->fetchColumn();
    echo "<p>PostgreSQL Version: $version</p>";
    
    // Check tables
    $stmt = $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'public' AND table_name IN ('app_user', 'recipe', 'pantry_item', 'recipe_ingredient')");
    $count = $stmt->fetchColumn();
    echo "<p>Tables found: $count/4</p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>[ERROR] Connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

// Test 2: Try with port
echo "<h3>Test 2: With port 5432</h3>";
try {
    $dsn = "pgsql:host=$host;port=5432;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>[OK] Connected with port 5432!</p>";
} catch (PDOException $e) {
    echo "<p style='color:orange'>[WARNING] Port 5432 failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 3: Check if PDO PostgreSQL driver exists
echo "<h3>Test 3: PDO Drivers</h3>";
$drivers = PDO::getAvailableDrivers();
echo "<p>Available PDO drivers: " . implode(', ', $drivers) . "</p>";
if (in_array('pgsql', $drivers)) {
    echo "<p style='color:green'>[OK] PostgreSQL driver available</p>";
} else {
    echo "<p style='color:red'>[ERROR] PostgreSQL driver NOT available!</p>";
}

// Test 4: Check environment variables
echo "<h3>Test 4: Environment Variables</h3>";
echo "<pre>";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'not set') . "\n";
echo "DB_NAME: " . (getenv('DB_NAME') ?: 'not set') . "\n";
echo "DB_USER: " . (getenv('DB_USER') ?: 'not set') . "\n";
echo "DB_PASSWORD: " . (getenv('DB_PASSWORD') ? '***set***' : 'not set') . "\n";
echo "</pre>";

// Test 5: Try different database names
echo "<h3>Test 5: Try Alternative Database Names</h3>";
$alt_names = ['postgres', 'template1', $username];
foreach ($alt_names as $alt_name) {
    try {
        $dsn = "pgsql:host=$host;dbname=$alt_name";
        $pdo = new PDO($dsn, $username, $password);
        echo "<p style='color:green'>[OK] Can connect to database: $alt_name</p>";
        break;
    } catch (PDOException $e) {
        echo "<p style='color:orange'>[WARNING] Cannot connect to: $alt_name</p>";
    }
}

echo "<h2>Next Steps:</h2>";
echo "<ul>";
echo "<li>If connection works here but not in db.php, check file paths</li>";
echo "<li>If port 5432 works, update db.php to include port</li>";
echo "<li>If different database name works, update db.php</li>";
echo "<li>If PDO driver missing, contact server admin</li>";
echo "</ul>";

echo "</body></html>";
?>

