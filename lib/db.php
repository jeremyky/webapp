<?php
/**
 * Database connection helper
 * Authors: Jeremy Ky, Ashley Wu, Shaunak Sinha
 * CS 4640 Sprint 3
 */

/**
 * Establish PDO connection to Postgres database
 * @return PDO
 */
function db_connect() {
    static $pdo = null;
    
    if ($pdo === null) {
        $host = getenv('DB_HOST') ?: 'localhost';
        $dbname = getenv('DB_NAME') ?: 'juh7hc';
        $username = getenv('DB_USER') ?: 'juh7hc';
        $password = getenv('DB_PASSWORD') ?: '';
        
        // Try connection with port first
        $dsn = "pgsql:host=$host;port=5432;dbname=$dbname";
        
        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Fallback: try without port
            try {
                $dsn = "pgsql:host=$host;dbname=$dbname";
                $pdo = new PDO($dsn, $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e2) {
                error_log("Database connection failed: " . $e2->getMessage());
                error_log("Attempted: host=$host, dbname=$dbname, user=$username");
                throw new Exception("Database connection failed: " . $e2->getMessage());
            }
        }
    }
    
    return $pdo;
}
