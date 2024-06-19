<?php
require '../../parts/Database-connection.php';

// 檢查有沒有帶參數進來，有的話轉換成整數(intval)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


// 如果不是0
if (!empty($id)) {
  $sql = "DELETE FROM articles WHERE id=$id";
  $pdo->query($sql);
}

// 停留在當下頁面
$backTo = 'forum.php';

// 如果這個不是空的
if (!empty($_SERVER['HTTP_REFERER'])) {
  $backTo = $_SERVER['HTTP_REFERER'];
}

header("Location: $backTo");
