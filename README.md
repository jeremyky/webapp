# Recipe Creator - Sprint 3

**Authors:** Jeremy Ky, Ashley Wu, Shaunak Sinha  
**Deployed URL:** https://cs4640.cs.virginia.edu/juh7hc/  
**Course:** CS 4640

## Project Structure

```
sprint1/
├── index.php              # Front controller
├── schema.sql             # Database schema
├── lib/                   # PHP library files
│   ├── db.php            # Database connection
│   ├── session.php       # Session management
│   ├── util.php          # Utility functions
│   ├── validate.php      # Validation with regex
│   └── repo.php          # Database repository functions
├── views/                 # View templates
│   ├── layout.header.php
│   ├── layout.footer.php
│   ├── home.php
│   ├── recipes.php
│   ├── upload.php
│   ├── pantry.php
│   ├── match.php
│   ├── cook.php
│   └── chat.php
├── api/                   # JSON API endpoints
│   └── recipes.php       # GET /api/recipes.php?q=chicken
└── assets/               # Static assets
    ├── styles.css
    └── uva.jpg
```

## Deployment Instructions

1. **Upload files to server:**
   ```bash
   # Upload all files to ~/public_html/recipe-creator/
   ```

2. **Create database and run schema:**
   ```bash
   # Connect to Postgres (via phpPgAdmin or psql)
   psql -U juh7hc -d juh7hc
   
   # Run schema.sql
   \i schema.sql
   ```

3. **Set database environment variables (if needed):**
   - The code uses environment variables for DB connection
   - Defaults work with standard cs4640 server setup

4. **Set permissions:**
   ```bash
   chmod 644 *.php
   chmod 755 lib/ views/ api/
   ```

## Features Implemented

### Requirements Checklist

**Front Controller Pattern** - All routes handled via `index.php?action=...`

**Arrays & Control Structures**
- Arrays: Used throughout (user data, recipe lists, pantry items)
- Loops: `foreach` in views and repository functions
- Selection: `switch` in front controller, `if/else` throughout

**Built-in Functions**
- `trim()`, `explode()`, `array_map()`, `preg_match()`, `json_encode()`, etc.

**User-defined Functions** (11 total)
- `db_connect()` - Database connection
- `user_id()` - Get current user
- `flash()` / `get_flash()` - Flash messages
- `csrf_token()` / `verify_csrf()` - CSRF protection
- `render()` - View rendering
- `json_out()` - JSON response
- `validate_recipe()` - Recipe validation
- `validate_pantry()` - Pantry validation
- `get_recipes()`, `save_recipe()`, `add_pantry_item()`, etc. - Repository functions

**$_GET & $_POST**
- GET: Search, filters, pagination (recipes, match)
- POST: Form submissions (upload, pantry add/delete)

**Server-side Validation**
- Two regex patterns:
  1. URL validation: `/^https?:\/\/[^\s]+$/i`
  2. Ingredient validation: `/^\s*([A-Za-z][A-Za-z\s\-]+|\d+(\.\d+)?\s?[A-Za-z]+.*)\s*$/`
- Error messages displayed to users
- Form values preserved on validation errors

**Form Submission & Handling**
- Upload recipe form (manual & URL)
- Pantry add/delete forms
- CSRF protection on all forms
- Flash messages for success/errors

**$_SESSION**
- User ID stored in session
- Flash messages
- CSRF tokens
- Old form input preservation

**State Management**
- **Cookies**: Last selected cuisine filter (`last_cuisine`)
- **Hidden Fields**: CSRF tokens in all forms
- **URL Rewriting**: Search params, pagination (in JSON API)

**Database (Postgres)**
- **Retrieve**: Users can view their recipes and pantry items
- **Add**: Upload recipes, add pantry items
- **Update**: (Structure ready, can be extended)
- **Delete**: Remove pantry items
- Multiple sessions supported via user_id

**JSON Endpoint**
- `/api/recipes.php?q=chicken&cuisine=italian&page=1`
- Returns paginated recipe results in JSON format

**Code Style**
- Consistent indentation (2 spaces)
- Meaningful variable/function names
- Comments throughout
- Newlines in output

## Testing the Application

1. **Add to Pantry:**
   - Navigate to Pantry page
   - Fill form and submit
   - Try invalid input to see validation

2. **Upload Recipe:**
   - Navigate to Upload page
   - Fill manual form or URL form
   - Check validation errors

3. **View Recipes:**
   - Browse recipes page
   - Use search and filter
   - Check cookie persistence (refresh, cuisine should be remembered)

4. **Match Recipes:**
   - Add items to pantry first
   - Go to Match page
   - Adjust slider and see matched recipes

5. **JSON API:**
   - Visit: `api/recipes.php?q=chicken`
   - Should return JSON with recipe data

## Database Schema

See `schema.sql` for complete schema. Main tables:
- `app_user` - Users
- `recipe` - Recipes
- `pantry_item` - User pantry items
- `recipe_ingredient` - Recipe ingredients (many-to-many)

