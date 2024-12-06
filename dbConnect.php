<?php
class ConnectPdo 
{

protected $dbh;
public function __construct()
{
 
$dbname = 'audio_catalog'; 
$username_db = 'root'; 
$password_db = ''; 
$host = 'localhost'; 

try {

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
$pdo = new PDO($dsn, $username_db, $password_db, [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
PDO::ATTR_EMULATE_PREPARES => false, 

]);
} catch (PDOException $e) {

die("Ошибка подключения к базе данных: " . $e->getMessage());

}

}
public function dbh()
{
    return $this->dbh;
}

}

$pdo = new ConnectPdo();
$pdo = $pdo->$dbh;



$ip = getenv( 'REMOTE_ADDR');


