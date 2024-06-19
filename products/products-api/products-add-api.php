<?php

require __DIR__ . '/../../parts/Database-connection.php';
header('Content-Type: application/json');
# 回應給用戶端的欄位 (格式 JSON)
$output = [
  'success' => false,
  'postData' => $_POST, # 除錯用
  'error' => '',
  'code' => 0, # 除錯或追踪程式碼
];

if (!empty($_POST['name'])) {
  $isPass = true;

  // 匯入sql

  if ($isPass) {
    //防止漏洞用prepare
    $sql = "INSERT INTO `products`(
       `name`, `pmt_id`, `material_details`, 
       `style`, `price`, `launched_time`,`updated_time`
      ) VALUES (  
        ?,?,?,
        ?,?,NOW(),?
     )"; //?佔位符
    $stmt = $pdo->prepare($sql);
    $updated_time = date("Y-m-d H:i:s");
    $stmt->execute([
      $_POST['name'],
      $_POST['main-type'],
      $_POST['material'],
      $_POST['style'],
      $_POST['price'],
      $updated_time
    ]);
    $output['success'] = boolval($stmt->rowCount());
  }
}

if ($isPass && $stmt->rowCount() > 0) {
  // Get the newly inserted product ID (assuming an auto-incrementing id)
  $productId = $pdo->lastInsertId();

  // Prepare the INSERT statement for product_photos
  $photoSql = "INSERT INTO `product_photos` (product_id, url) VALUES (?, ?)";
  $photoStmt = $pdo->prepare($photoSql);

  // Sanitize and validate the photo URL before binding
  $photoUrl = filter_var($_POST['url'], FILTER_SANITIZE_URL); // Sanitize URL
  $photoStmt->execute([$productId, $photoUrl]);
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
