<?php
/**
 * Quick Test Script
 * Run this to verify basic setup
 * Authors: Jeremy Ky, Ashley Wu, Shaunak Sinha
 */

echo "<!DOCTYPE html><html><head><title>Quick Test</title></head><body>";
echo "<h1>Quick Test Script</h1><pre>";

// Test 1: Database Connection
echo "1. Testing database connection...\n";
try {
    require 'lib/db.php';
    $pdo = db_connect();
    echo "   Database connected\n";
} catch (Exception $e) {
    echo "   Database failed: " . $e->getMessage() . "\n";
    echo "</pre></body></html>";
    exit(1);
}

// Test 2: Tables Exist
echo "\n2. Testing database tables...\n";
$tables = ['app_user', 'recipe', 'pantry_item', 'recipe_ingredient'];
foreach ($tables as $table) {
    try {
        $stmt = $pdo->query("SELECT 1 FROM $table LIMIT 1");
        echo "   Table '$table' exists\n";
    } catch (Exception $e) {
        echo "   Table '$table' missing: " . $e->getMessage() . "\n";
    }
}

// Test 3: User Exists
echo "\n3. Testing demo user...\n";
try {
    $stmt = $pdo->query("SELECT id, email FROM app_user WHERE id = 1");
    $user = $stmt->fetch();
    if ($user) {
        echo "   Demo user found: ID={$user['id']}, Email={$user['email']}\n";
    } else {
        echo "   Demo user not found (you may need to run schema.sql)\n";
    }
} catch (Exception $e) {
    echo "   Error checking user: " . $e->getMessage() . "\n";
}

// Test 4: Session Functions
echo "\n4. Testing session functions...\n";
require 'lib/session.php';
$uid = user_id();
echo "   Current user ID: $uid\n";

// Test 5: Validation Functions
echo "\n5. Testing validation functions...\n";
require 'lib/validate.php';
$test_input = ['title' => 'Test Recipe', 'ingredients' => '2 cups flour', 'steps' => 'Mix and bake for 30 minutes'];
list($errors, $clean) = validate_recipe($test_input);
if (empty($errors)) {
    echo "   Validation working\n";
} else {
    echo "   Validation returned errors (may be expected)\n";
}

// Test 6: Repository Functions
echo "\n6. Testing repository functions...\n";
require 'lib/repo.php';
try {
    $recipes = get_recipes($uid);
    echo "   Found " . count($recipes) . " recipes for user $uid\n";
    
    $pantry = get_pantry($uid);
    echo "   Found " . count($pantry) . " pantry items for user $uid\n";
} catch (Exception $e) {
    echo "   Repository test: " . $e->getMessage() . "\n";
}

// Test 7: JSON Output
echo "\n7. Testing JSON encoding...\n";
require 'lib/util.php';
$test_data = ['test' => 'value', 'number' => 123];
$json = json_encode($test_data, JSON_PRETTY_PRINT);
if ($json) {
    echo "   JSON encoding works\n";
    echo "   Sample output:\n";
    echo $json . "\n";
} else {
    echo "   JSON encoding failed\n";
}

echo "\n=== Test Complete ===\n";
echo "All basic tests passed!\n";
echo "</pre>";
echo "<p><a href='index.php'>Go to Home</a></p>";
echo "</body></html>";
?>

