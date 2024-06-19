<?php

require  '.././parts/Database-connection.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!empty($id)) {
  $sql = "DELETE FROM `midterm`.order WHERE id =$id";
  $pdo->query($sql);
}

$backTo = 'order-list.php';

if (!empty($_SERVER['HTTP_REFERER'])) {
  $backTo = $_SERVER['HTTP_REFERER'];
}

header("Location: $backTo");
