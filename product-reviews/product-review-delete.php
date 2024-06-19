<?php
// require __DIR__ .'/parts/admin-required.php';
require __DIR__ . '/../parts/Database-connection.php';


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// 已變成整數 不需要用prepare
if (!empty($id)) {
    $sql = "DELETE FROM product_reviews WHERE id=$id"; //刪除要刪除的變數
    $pdo->query($sql);
}
//1.不知道是否已景刪除過2.刪除後第二頁第一個跳到第一頁最後一個

//以防漏洞(不要讓別人知道原始資料從哪裡來)
$backTo = 'product-review-all-id.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $backTo = $_SERVER['HTTP_REFERER'];
}

header("Location: $backTo");
