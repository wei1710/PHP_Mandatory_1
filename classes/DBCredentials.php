<?php

require_once 'vendor/autoload.php';

Class DBCredentials
{
  protected string $host = '';
  protected string $dbname = '';
  protected string $user = '';
  protected string $password = '';

  public function __construct()
  {
    $jawsdb_url = getenv('JAWSDB_URL');

    if ($jawsdb_url) {
      $url_parts = parse_url($jawsdb_url);

      $this->host = $url_parts['host'];
      $this->dbname = ltrim($url_parts['path'], '/');
      $this->user = $url_parts['user'];
      $this->password = $url_parts['pass'];
    } else {
      $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
      $dotenv->safeLoad();

      $this->host = $_ENV['DB_HOST'] ?? 'localhost';
      $this->dbname = $_ENV['DB_DATABASE'] ?? 'company';
      $this->user = $_ENV['DB_USERNAME'] ?? 'root';
      $this->password = $_ENV['DB_PASSWORD'] ?? '';
      error_log("Warning: JAWSDB_URL not found. Using local .env settings.");
    }
  }
}
