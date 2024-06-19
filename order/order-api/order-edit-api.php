<?php
date_default_timezone_set('Asia/Taipei');
require '../../parts/Database-connection.php';
header("Content-Type: application/json");

# 回應給用戶端的欄位(格式JSON)
$outPut = [
  'success' => false,
  'postData' => $_POST, # 除錯用
  'error' => '',
  'code' => 0, # 除錯或追蹤程式碼
];

if (!empty($_POST['id']) and !empty($_POST['total_cost'])) {
  $isPass = true;
  // TODO: 檢查資料格式

  // 檢查總金額輸入
  if (mb_strlen($_POST['total_cost']) < 1) {
    $isPass = false;
    $outPut['error'] = '總金額未輸入';
  }

  // 讓優惠券可以是空值
  $coupon_id = null;
  if (!empty($_POST['coupon_id'])) {
    $coupon_id = $_POST['coupon_id'];
  }

  if ($isPass) {
    #避免 SQL injection
    $sql = "UPDATE `midterm`.`order` SET
    `coupon_id` = ?,
    `shipping_method`= ?,
    `payment_method`= ?,
    `total_cost`= ?,
    `order_status`= ?,
    `order_creation_time`= ?,
    `receiver`= ?,
    `receiver_address`= ?,
    `receiver_phone`= ?
    where  `id` = ?";

    // 先插入訂單
    $currentDateTime = date("Y-m-d H:i:s");
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$coupon_id, $_POST['shipping_method'], $_POST['payment_method'], $_POST['total_cost'], $_POST['order_status'], $currentDateTime, $_POST['receiver'], $_POST['receiver_address'], $_POST['receiver_phone'], $_POST['id']]);

    // 獲取剛剛插入訂單自動生成的order_id
    $order_id_fk = $pdo->lastInsertId();

    // 讓評價ID可以是空值
    $product_reviews_id = null;
    if (!empty($_POST['product_reviews_id'])) {
      $product_reviews_id = $_POST['product_reviews_id'];
    }

    // ID 插入訂單詳情
  //   $sql2 = "UPDATE `midterm`.`order_details` SET
  //   `order_id` = ?, 
  //   `product_name` = ?,
  //   `product_size` = ?,
  //   `product_color` = ?,
  //   `product_amount` = ?,
  //   `price` = ?,
  //   `product_amount_total` = ?,
  //   `product_reviews_id` = ?,
  //   `user_id`= ?
  //   where  `id` = ?"
  //  ;


    // // 獲取前端提交的商品資料的總數
    // $productCount = count($_POST['product_name']);


    // // 準備SQL語句
    // $stmt2 = $pdo->prepare($sql2);

    // // 檢查是否成功準備SQL語句
    // if ($stmt2) {
    //   // 遍歷所有商品，插入到資料庫中
    //   for ($i = 0; $i < $productCount; $i++) {
    //     // 獲取當前商品的資料
    //     $productName = $_POST['product_name'][$i];
    //     $productSize = $_POST['product_size'][$i];
    //     $productColor = $_POST['product_color'][$i];
    //     $productAmount = $_POST['product_amount'][$i];
    //     $price = $_POST['price'][$i];
    //     $productAmountTotal = $_POST['product_amount_total'][$i];

    //     // 執行SQL語句
    //     $stmt2->execute([$order_id_fk, $productName, $productSize, $productColor, $productAmount, $price, $productAmountTotal, $product_reviews_id,$_POST['user_id']]);
    //   }
    // } else {
    //   // 如果無法成功準備SQL語句，進行相應的錯誤處理
    //   echo "Failed to prepare SQL statement";
    // }



    
    // $outPut['success2'] = boolval($stmt2->rowCount());
    $outPut['success'] = boolval($stmt->rowCount());
  }
}

echo json_encode($outPut, JSON_UNESCAPED_UNICODE);
