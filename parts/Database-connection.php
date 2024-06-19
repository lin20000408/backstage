<?php
$env = parse_ini_file(dirname(__DIR__) . '/.env');

$host = $env["DATABASE_HOST"];
$username = $env["DATABASE_USERNAME"];
$password = $env["DATABASE_PASSWORD"];
$db_name = $env["DATABASE_NAME"];

$dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";

$pdo_options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, #錯誤訊息
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]; #pdo相關設定

$pdo = new PDO($dsn, $username, $password, $pdo_options);
if (!isset($_SESSION)) {
  session_start();
}



/* 記得要require這個檔案 


撈資料庫範例
-----------------------------------------------------
$sql = "SELECT * FROM user"; #取user資料
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll();
-----------------------------------------------------*/

/* api部分範例
-------------------------------------------------------
  header("Content-Type: application/json");
  echo json_encode($rows,JSON_UNESCAPED_UNICODE);
------------------------------------------------------*/

/* $conn = mysqli_init();
// // mysqli_ssl_set($conn, NULL, NULL, "{path to CA cert}", NULL, NULL);
// mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306);

// //If connection failed, show the error
// if (mysqli_connect_errno()) {
//     die('Failed to connect to MySQL: ' . mysqli_connect_error());
// }
*/
