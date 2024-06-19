<?php
require '../../parts/Database-connection.php'; // 引入資料庫連線設定


header('Content-Type: application/json'); // 設定回應的內容類型為 JSON


$output = [
  'success' => false, // 預設回應為失敗
  'postData' => $_POST, // 儲存收到的 POST 資料
  'error' => '', // 錯誤訊息
  'code' => 0, // 錯誤代碼，預設為 0
];

if (!empty($_POST['product_name'])) { // 如果收到的 POST 中包含產品名稱
  $isPass = true; // 檢查是否通過條件，預設為 true

  if ($isPass) { // 如果通過條件
    $restock_time = null; // 初始化進貨時間為空

    if (!empty($_POST['restock_time'])) { // 如果收到的 POST 中有進貨時間
      $t = strtotime($_POST['restock_time']); // 將進貨時間轉換為時間戳
      if ($t !== false) { // 如果轉換成功
        $restock_time = date("Y-m-d", $t); // 將時間戳轉換為日期格式
      }
    }

    // 先從 products 資料表中獲取 product_id
    $product_name = $_POST['product_name'];  // 從 POST 中取得產品名稱
    $stmt = $pdo->prepare("SELECT id FROM products WHERE name = ?"); // 準備 SQL 查詢
    $stmt->execute([$product_name]); // 執行 SQL 查詢
    $product = $stmt->fetch();  // 取得查詢結果的第一行

    if ($product) {  // 如果找到對應的產品
      $product_id = $product['id'];  // 取得產品的 ID

      // 再從 product_variants 資料表中獲取 product_variants_id
      $stmt = $pdo->prepare("SELECT id FROM product_variants WHERE product_id = ?"); // 準備 SQL 查詢
      $stmt->execute([$product_id]); // 執行 SQL 查詢
      $product_variant = $stmt->fetch(); // 取得查詢結果的第一行

      if ($product_variant) { // 如果找到對應的產品變體
        $product_variants_id = $product_variant['id'];  // 取得產品變體的 ID

        // 插入到 restock 資料表
        $sql = "INSERT INTO `restock`(
          `product_variants_id`, `product_name`, `amount`, `restock_time`
        ) VALUES (
          ?, ?, ?, ?
        )"; // 準備 SQL 插入語句
        $stmt = $pdo->prepare($sql);  // 準備 SQL 語句
        $stmt->execute([  // 執行 SQL 插入語句
          $product_variants_id,  // 產品變體的 ID
          $product_name,  // 產品名稱
          $_POST['amount'],  // 進貨數量
          $restock_time // 進貨時間
        ]);

        $output['success'] = boolval($stmt->rowCount());  // 更新成功的狀態
      } else {
        $output['error'] = "找不到對應的 product_variants_id"; // 找不到產品變體的錯誤訊息
        $output['code'] = 1; // 錯誤代碼為 1
      }
    } else {
      $output['error'] = "找不到對應的 product_id"; // 找不到產品的錯誤訊息
      $output['code'] = 2;  // 錯誤代碼為 2
    }
  }
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);  // 將回應轉換為 JSON 格式並輸出
