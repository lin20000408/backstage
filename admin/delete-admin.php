<?php
require __DIR__ . '/required-admin.php';
require __DIR__ . '/../parts/Database-connection.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(!empty($id)) {
  $sql ="DELETE FROM `midterm`.admin WHERE id=$id";
  $pdo->query($sql);
}

$backTo = 'admin.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
  $backTo = $_SERVER['HTTP_REFERER'];
}
header("Location: $backTo");

