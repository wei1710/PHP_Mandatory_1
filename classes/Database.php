<?php

require_once 'DBCredentials.php';

Class Database extends DBCredentials
{
  protected ?PDO $pdo;

  public function __construct()
  {
    parent::__construct();

    $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    try {
      $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
    } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
    }
  }

  public function __destruct()
  {
    $this->pdo = null;
  }
}
