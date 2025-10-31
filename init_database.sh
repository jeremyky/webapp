#!/bin/bash
# Database initialization script
# Run this on the server via SSH

echo "=== Database Setup Script ==="
echo ""

# Get database name (usually same as username)
DBNAME=${USER:-juh7hc}
USERNAME=$DBNAME

echo "Using database: $DBNAME"
echo ""

# Step 1: Create tables
echo "Step 1: Creating tables..."

psql -U "$USERNAME" -d "$DBNAME" << 'SQL'
-- Create app_user table
CREATE TABLE IF NOT EXISTS app_user (
  id SERIAL PRIMARY KEY,
  email TEXT UNIQUE NOT NULL,
  created_at TIMESTAMP DEFAULT NOW()
);

-- Create recipe table
CREATE TABLE IF NOT EXISTS recipe (
  id SERIAL PRIMARY KEY,
  user_id INT REFERENCES app_user(id) ON DELETE CASCADE,
  title TEXT NOT NULL,
  image_url TEXT,
  steps TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT NOW()
);

-- Create pantry_item table
CREATE TABLE IF NOT EXISTS pantry_item (
  id SERIAL PRIMARY KEY,
  user_id INT REFERENCES app_user(id) ON DELETE CASCADE,
  ingredient TEXT NOT NULL,
  quantity NUMERIC(10,2) NOT NULL DEFAULT 0,
  unit TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT NOW()
);

-- Create recipe_ingredient table
CREATE TABLE IF NOT EXISTS recipe_ingredient (
  id SERIAL PRIMARY KEY,
  recipe_id INT REFERENCES recipe(id) ON DELETE CASCADE,
  line TEXT NOT NULL
);

-- Create indexes
CREATE INDEX IF NOT EXISTS idx_recipe_user_id ON recipe(user_id);
CREATE INDEX IF NOT EXISTS idx_pantry_user_id ON pantry_item(user_id);
CREATE INDEX IF NOT EXISTS idx_recipe_ingredient_recipe_id ON recipe_ingredient(recipe_id);
SQL

if [ $? -eq 0 ]; then
    echo "[OK] Tables created successfully"
else
    echo "[ERROR] Table creation failed"
    exit 1
fi

echo ""

# Step 2: Insert demo user
echo "Step 2: Creating demo user..."

psql -U "$USERNAME" -d "$DBNAME" << 'SQL'
INSERT INTO app_user (id, email) 
VALUES (1, 'demo@example.com')
ON CONFLICT (id) DO NOTHING;

SELECT setval('app_user_id_seq', 1, true);
SQL

if [ $? -eq 0 ]; then
    echo "[OK] Demo user created"
else
    echo "[WARNING]  Demo user may already exist"
fi

echo ""

# Step 3: Verify setup
echo "Step 3: Verifying setup..."

psql -U "$USERNAME" -d "$DBNAME" << 'SQL'
SELECT 
    (SELECT COUNT(*) FROM app_user) as users,
    (SELECT COUNT(*) FROM recipe) as recipes,
    (SELECT COUNT(*) FROM pantry_item) as pantry_items;
SQL

echo ""
echo "=== Setup Complete ==="
echo ""
echo "Test your database at:"
echo "https://cs4640.cs.virginia.edu/$USERNAME/check_database.php"

