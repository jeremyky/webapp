<?php
/**
 * Server-side validation functions
 * Authors: Jeremy Ky, Ashley Wu, Shaunak Sinha
 * CS 4640 Sprint 3
 */

/**
 * Validate recipe form input
 * @param array $input
 * @param string $mode 'manual' or 'url'
 * @return array [errors, cleaned_data]
 */
function validate_recipe($input, $mode = 'manual') {
    $errors = [];
    $clean = [];
    
    if ($mode === 'url') {
        // Validate URL using regex
        $url_pattern = '/^https?:\/\/[^\s]+$/i';
        $url = trim($input['url'] ?? '');
        
        if (empty($url)) {
            $errors['url'] = 'Recipe URL is required';
        } elseif (!preg_match($url_pattern, $url)) {
            $errors['url'] = 'Please enter a valid URL (must start with http:// or https://)';
        } else {
            $clean['url'] = $url;
        }
        
        return [$errors, $clean];
    }
    
    // Manual entry validation
    $title = trim($input['title'] ?? '');
    if (empty($title)) {
        $errors['title'] = 'Recipe title is required';
    } elseif (mb_strlen($title) < 3) {
        $errors['title'] = 'Recipe title must be at least 3 characters';
    } else {
        $clean['title'] = $title;
    }
    
    $image_url = trim($input['image'] ?? '');
    if (!empty($image_url)) {
        $url_pattern = '/^https?:\/\/[^\s]+$/i';
        if (!preg_match($url_pattern, $image_url)) {
            $errors['image'] = 'Image URL must be a valid URL';
        } else {
            $clean['image_url'] = $image_url;
        }
    }
    
    $ingredients = trim($input['ingredients'] ?? '');
    if (empty($ingredients)) {
        $errors['ingredients'] = 'Ingredients are required';
    } else {
        // Validate ingredient lines using regex (allows names or "quantity unit name")
        $ingredient_pattern = '/^\s*([A-Za-z][A-Za-z\s\-]+|\d+(\.\d+)?\s?[A-Za-z]+.*)\s*$/';
        $lines = explode("\n", $ingredients);
        $valid_lines = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                if (!preg_match($ingredient_pattern, $line)) {
                    $errors['ingredients'] = 'Each ingredient line must be valid (e.g., "Chicken" or "2 tbsp olive oil")';
                    break;
                }
                $valid_lines[] = $line;
            }
        }
        
        if (empty($errors['ingredients'])) {
            $clean['ingredients'] = implode("\n", $valid_lines);
        }
    }
    
    $steps = trim($input['steps'] ?? '');
    if (empty($steps)) {
        $errors['steps'] = 'Recipe steps are required';
    } elseif (mb_strlen($steps) < 10) {
        $errors['steps'] = 'Recipe steps must be at least 10 characters';
    } else {
        $clean['steps'] = $steps;
    }
    
    return [$errors, $clean];
}

/**
 * Validate pantry item input
 * @param array $input
 * @return array [errors, cleaned_data]
 */
function validate_pantry($input) {
    $errors = [];
    $clean = [];
    
    $name = trim($input['name'] ?? '');
    if (empty($name)) {
        $errors['name'] = 'Ingredient name is required';
    } elseif (mb_strlen($name) < 2) {
        $errors['name'] = 'Ingredient name must be at least 2 characters';
    } else {
        $clean['ingredient'] = $name;
    }
    
    $quantity = $input['quantity'] ?? '';
    if (!isset($input['quantity']) || $input['quantity'] === '') {
        $errors['quantity'] = 'Quantity is required';
    } elseif (!is_numeric($quantity) || floatval($quantity) < 0) {
        $errors['quantity'] = 'Quantity must be a number greater than or equal to 0';
    } else {
        $clean['quantity'] = floatval($quantity);
    }
    
    $unit = trim($input['unit'] ?? '');
    if (empty($unit)) {
        $errors['unit'] = 'Unit is required';
    } else {
        $allowed_units = ['lb', 'oz', 'g', 'kg', 'cup', 'tbsp', 'tsp', 'piece', 'ml'];
        if (!in_array($unit, $allowed_units)) {
            $errors['unit'] = 'Invalid unit selected';
        } else {
            $clean['unit'] = $unit;
        }
    }
    
    return [$errors, $clean];
}

