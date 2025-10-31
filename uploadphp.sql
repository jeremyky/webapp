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

-- Insert demo user
INSERT INTO app_user (id, email) 
VALUES (1, 'demo@example.com')
ON CONFLICT (id) DO NOTHING;

-- Set sequence
SELECT setval('app_user_id_seq', 1, true);