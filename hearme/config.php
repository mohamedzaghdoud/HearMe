<?php
// config.php
class Config {
    public static function connect() {
        $host = '127.0.0.1';
        $db   = 'hearme';
        $user = 'root';
        $pass = ''; // adapter selon ton environnement
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            die('DB Connexion Error: ' . $e->getMessage());
        }
    }
}
