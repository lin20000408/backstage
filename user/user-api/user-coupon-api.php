<?php
// 这里是您的PHP代码
require __DIR__ . '/../../parts/Database-connection.php';
header('Content-Type: application/json');

$output = [
  'success' => false,
  'postData' => $_POST, # 除錯用
  'error' => '',
  'code' => 0, # 除錯或追踪程式碼
];

// 获取搜索名称
$search_name = isset ($_POST['account']) ? $_POST['account'] : '';

// echo $search_name;
$rows = [];


if (isset ($_POST['status'])) {
  $status = $_POST['status'];

  if ($status == 'both') {
    $search_both = '未使用';
    $sql = "SELECT id, name ,account, email ,address, coupon_name,usage_status ,usage_time FROM `midterm`.user 
                      JOIN coupon_send_management 
                      ON user.id = coupon_send_management.user_id
                      WHERE account LIKE ? AND usage_status IN (?, '已使用')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search_name%", $search_both]);
    $rows = $stmt->fetchAll();
    // echo($rows);
    $json_rows = json_encode($rows, JSON_UNESCAPED_UNICODE);
    echo $json_rows;
    // echo "<script>var rows = " . $json_rows . ";</script>";
    // var_dump($rows);

  } else {

    $sql = "SELECT id, name ,account, email ,address, coupon_name ,usage_status ,usage_time FROM `midterm`.user 
                      JOIN coupon_send_management 
                      ON user.id = coupon_send_management.user_id
                      WHERE account LIKE ? AND usage_status = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search_name%", $status]);

    $rows = $stmt->fetchAll();
    $json_rows = json_encode($rows, JSON_UNESCAPED_UNICODE); //解析php raws 轉成 javascript值
    echo $json_rows;
    // echo "<script>var rows = " . $json_rows . ";</script>";  回傳給javascript值
  }
} else {
  echo "未收到 status 參數";
}
