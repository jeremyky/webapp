<?php
/**
 * Populate Database with Sample Data (plain-text output)
 * Visit: https://cs4640.cs.virginia.edu/juh7hc/recipe-creator/populate_sample_data.php
 * Delete this file after seeding.
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/lib/db.php';
require __DIR__ . '/lib/repo.php';
require __DIR__ . '/lib/session.php';

function slugify($s) {
    $s = strtolower(trim($s));
    $s = preg_replace('~[^a-z0-9]+~', '-', $s);
    return trim($s, '-');
}

$pdo = function_exists('db') ? db() : db_connect();
$userId = function_exists('user_id') ? user_id() : 1;
if (!$userId) $userId = 1;

/* wrappers to support either your repo helpers or direct PDO */
if (!function_exists('repo_add_pantry_item')) {
    function repo_add_pantry_item($userId, $item) {
        if (function_exists('add_pantry_item')) {
            return add_pantry_item($userId, $item);
        }
        global $pdo;
        $sql = 'INSERT INTO pantry_item (user_id, ingredient, quantity, unit)
                VALUES (:uid,:ing,:qty,:unit)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':uid'  => $userId,
            ':ing'  => $item['ingredient'],
            ':qty'  => $item['quantity'],
            ':unit' => $item['unit'],
        ]);
    }
}

if (!function_exists('repo_save_recipe')) {
    function repo_save_recipe($userId, $data) {
        if (function_exists('save_recipe')) {
            return save_recipe($userId, $data);
        }
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO recipe (user_id,title,image_url,steps)
                               VALUES (:uid,:t,:img,:steps) RETURNING id');
        $stmt->execute([
            ':uid'   => $userId,
            ':t'     => $data['title'],
            ':img'   => $data['image_url'],
            ':steps' => $data['steps'],
        ]);
        return (int)$stmt->fetchColumn();
    }
}

if (!function_exists('repo_save_recipe_ingredients')) {
    function repo_save_recipe_ingredients($recipeId, array $lines) {
        if (function_exists('save_recipe_ingredients')) {
            return save_recipe_ingredients($recipeId, $lines);
        }
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO recipe_ingredient (recipe_id,line) VALUES (:rid,:line)');
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $stmt->execute([':rid' => $recipeId, ':line' => $line]);
        }
    }
}

/* recipes with real image URLs */
$recipes = [
    'Spaghetti Carbonara' => [
        'image_url' => 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=800&h=600&fit=crop',
        'steps' => "1. Boil water and cook spaghetti until al dente\n2. Cook pancetta until crispy\n3. Beat eggs with parmesan cheese\n4. Toss hot pasta with pancetta and egg mixture\n5. Season with black pepper and serve",
        'ingredients' => [
            '1 lb spaghetti',
            '4 oz pancetta, diced',
            '3 large eggs',
            '1 cup parmesan cheese, grated',
            'Black pepper to taste'
        ],
    ],
    'Chicken Tikka Masala' => [
        'image_url' => 'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=800&h=600&fit=crop',
        'steps' => "1. Marinate chicken in yogurt and spices\n2. Brown chicken\n3. Saute onions and garlic\n4. Add tomatoes, cream, and spices\n5. Simmer and add chicken\n6. Serve with rice",
        'ingredients' => [
            '2 lbs chicken breast, cubed',
            '1 cup plain yogurt',
            '2 tbsp tikka masala spice',
            '1 can diced tomatoes',
            '1/2 cup heavy cream',
            '1 onion, diced',
            '3 cloves garlic, minced',
            'Basmati rice'
        ],
    ],
    'Tacos Al Pastor' => [
        'image_url' => 'https://images.unsplash.com/photo-1565299585323-38174c1d5d1a?w=800&h=600&fit=crop',
        'steps' => "1. Marinate pork with pineapple and spices\n2. Grill or roast pork\n3. Warm tortillas\n4. Serve with onion, cilantro, pineapple\n5. Add lime and hot sauce",
        'ingredients' => [
            '2 lbs pork shoulder, sliced',
            '1 cup pineapple, diced',
            '1 onion, sliced',
            'Fresh cilantro',
            'Corn tortillas',
            'Lime wedges'
        ],
    ],
    'Beef Stir Fry' => [
        'image_url' => 'https://images.unsplash.com/photo-1563379091339-03246963d19a?w=800&h=600&fit=crop',
        'steps' => "1. Slice beef thinly\n2. Stir fry beef; remove\n3. Stir fry vegetables\n4. Add sauce and return beef\n5. Serve over rice",
        'ingredients' => [
            '1 lb beef sirloin, sliced',
            '2 bell peppers, sliced',
            '1 onion, sliced',
            '2 tbsp soy sauce',
            '1 tbsp oyster sauce',
            '2 cloves garlic, minced',
            'Cooked white rice'
        ],
    ],
    'Margherita Pizza' => [
        'image_url' => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=800&h=600&fit=crop',
        'steps' => "1. Preheat oven to 475 F\n2. Roll dough\n3. Spread tomato sauce\n4. Add mozzarella and basil\n5. Drizzle oil, bake 10 to 12 min",
        'ingredients' => [
            '1 pizza dough',
            '1/2 cup tomato sauce',
            '8 oz fresh mozzarella',
            'Fresh basil leaves',
            '2 tbsp olive oil'
        ],
    ],
    'Pad Thai' => [
        'image_url' => 'https://images.unsplash.com/photo-1559314809-0c8c4a1a5d5e?w=800&h=600&fit=crop',
        'steps' => "1. Soak rice noodles\n2. Scramble eggs; set aside\n3. Cook shrimp\n4. Add noodles and sauce\n5. Toss with sprouts and peanuts\n6. Serve with lime",
        'ingredients' => [
            '8 oz rice noodles',
            '1/2 lb shrimp',
            '2 eggs',
            '2 tbsp fish sauce',
            '2 tbsp tamarind paste',
            '1/2 cup bean sprouts',
            '1/4 cup peanuts, crushed',
            'Lime wedges'
        ],
    ],
    'Greek Salad' => [
        'image_url' => 'https://images.unsplash.com/photo-1546793665-c74683f339c1?w=800&h=600&fit=crop',
        'steps' => "1. Dice tomatoes and cucumber\n2. Slice red onion\n3. Crumble feta\n4. Combine with olives\n5. Dress with oil and lemon; oregano, salt",
        'ingredients' => [
            '2 tomatoes, diced',
            '1 cucumber, diced',
            '1 red onion, sliced',
            '6 oz feta cheese',
            '1/2 cup kalamata olives',
            '3 tbsp olive oil',
            '1 lemon, juiced',
            'Dried oregano'
        ],
    ],
    'Chicken Noodle Soup' => [
        'image_url' => 'https://images.unsplash.com/photo-1572441713132-51c75654db73?w=800&h=600&fit=crop',
        'steps' => "1. Simmer chicken with vegetables\n2. Shred chicken\n3. Add noodles to broth\n4. Return chicken\n5. Season and serve",
        'ingredients' => [
            '2 lbs chicken thighs',
            '8 cups chicken broth',
            '2 carrots, sliced',
            '2 celery stalks, sliced',
            '1 onion, diced',
            '8 oz egg noodles',
            'Salt and pepper'
        ],
    ],
];

/* pantry items */
$pantry_items = [
    ['ingredient' => 'Chicken Breast', 'quantity' => 2.5, 'unit' => 'lb'],
    ['ingredient' => 'Garlic',         'quantity' => 1,   'unit' => 'piece'],
    ['ingredient' => 'Olive Oil',      'quantity' => 500, 'unit' => 'ml'],
    ['ingredient' => 'Rice',           'quantity' => 2,   'unit' => 'kg'],
    ['ingredient' => 'Broccoli',       'quantity' => 1,   'unit' => 'lb'],
    ['ingredient' => 'Eggs',           'quantity' => 12,  'unit' => 'piece'],
    ['ingredient' => 'Flour',          'quantity' => 5,   'unit' => 'cup'],
    ['ingredient' => 'Salt',           'quantity' => 1,   'unit' => 'tsp'],
    ['ingredient' => 'Soy Sauce',      'quantity' => 250, 'unit' => 'ml'],
    ['ingredient' => 'Onion',          'quantity' => 3,   'unit' => 'piece'],
];

/* HTML output */
echo "<!DOCTYPE html><html><head><title>Populate Sample Data</title>";
echo "<style>body{font-family:ui-monospace,Menlo,monospace;padding:20px;line-height:1.6}
.ok{color:#16a34a}.error{color:#dc2626} code{background:#111827;color:#e5e7eb;padding:2px 6px;border-radius:6px}</style>";
echo "</head><body><h1>Populating Sample Data</h1>";

echo "<h2>Adding Recipes</h2>";
$recipeCount = 0;
$existsStmt = $pdo->prepare('SELECT id FROM recipe WHERE user_id = :uid AND title = :t LIMIT 1');

foreach ($recipes as $title => $data) {
    try {
         $existsStmt->execute([':uid' => $userId, ':t' => $title]);
         $existingId = $existsStmt->fetchColumn();
         
         $img = $data['image_url'] ?? 'https://picsum.photos/seed/' . rawurlencode(slugify($title)) . '/800/600';
         
         if ($existingId) {
             // Update existing recipe with image
             $updateStmt = $pdo->prepare('UPDATE recipe SET image_url = :img WHERE id = :id');
             $updateStmt->execute([':img' => $img, ':id' => $existingId]);
             echo "<p class='ok'>Updated image for: ".htmlspecialchars($title)." <code>$img</code></p>";
             continue;
         }

         $recipeId = repo_save_recipe($userId, [
             'title'     => $title,
             'image_url' => $img,
             'steps'     => $data['steps'],
         ]);
        repo_save_recipe_ingredients($recipeId, $data['ingredients']);
        $recipeCount++;
        echo "<p class='ok'>Added: ".htmlspecialchars($title)." <code>$img</code></p>";
    } catch (Throwable $e) {
        echo "<p class='error'>Error adding ".htmlspecialchars($title).": ".htmlspecialchars($e->getMessage())."</p>";
    }
}

echo "<h2>Adding Pantry Items</h2>";
$pantryCount = 0;
$existsPantry = $pdo->prepare('SELECT 1 FROM pantry_item WHERE user_id=:uid AND ingredient=:ing LIMIT 1');

foreach ($pantry_items as $item) {
    try {
        $existsPantry->execute([':uid'=>$userId, ':ing'=>$item['ingredient']]);
        if ($existsPantry->fetchColumn()) {
            echo "<p class='ok'>Skipped (already in pantry): ".htmlspecialchars($item['ingredient'])."</p>";
            continue;
        }
        repo_add_pantry_item($userId, $item);
        $pantryCount++;
        echo "<p class='ok'>Added: ".htmlspecialchars($item['quantity'].' '.$item['unit'].' '.$item['ingredient'])."</p>";
    } catch (Throwable $e) {
        echo "<p class='error'>Error adding ".htmlspecialchars($item['ingredient']).": ".htmlspecialchars($e->getMessage())."</p>";
    }
}

echo "<h2>Summary</h2>";
echo "<p class='ok'>Added $recipeCount recipes</p>";
echo "<p class='ok'>Added $pantryCount pantry items</p>";

echo "<h2>Test Your App</h2>";
$base = dirname($_SERVER['SCRIPT_NAME']);
echo "<ul>";
echo "<li><a href='{$base}/index.php?action=recipes'>View Recipes</a></li>";
echo "<li><a href='{$base}/index.php?action=pantry'>View Pantry</a></li>";
echo "<li><a href='{$base}/index.php?action=match'>Match Recipes</a></li>";
echo "<li><a href='{$base}/api/recipes.php?q=chicken'>View JSON API</a></li>";
echo "</ul>";

echo "<p>Reminder: delete populate_sample_data.php before the demo.</p>";
echo "</body></html>";
