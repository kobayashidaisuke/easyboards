<?php
require __DIR__ . '/../vendor/autoload.php';

function dbConnect()
{
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWD'];
        $database = $_ENV['DB_DATABASE'];

        $dsn = 'mysql:dbname=' . $database . ';host=' . $host . ';charset=utf8mb4';
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $pdo;
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}
