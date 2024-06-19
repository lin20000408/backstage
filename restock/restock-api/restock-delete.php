<?php
// 引入資料庫連線
require  '../../parts/Database-connection.php' ;

// 從 GET 取得 id，如果不存在則預設為 0
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


// 如果 id 不為空，則執行刪除操作
if (!empty($id)) {
  // 準備 SQL 刪除語句
  $sql = "DELETE FROM `midterm`.restock WHERE id=$id";
  // 執行 SQL 刪除語句
  $pdo->query($sql);
}

// 設定返回頁面為 'restock.php'
$backTo = 'restock.php';
// 如果有來源頁面，則將返回頁面設定為來源頁面
if (!empty($_SERVER['HTTP_REFERER'])) {
  $backTo = $_SERVER['HTTP_REFERER'];
}

// 導向至返回頁面
header("Location: $backTo");

