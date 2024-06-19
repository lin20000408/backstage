<?php
// 引入必要的檔案
require '../../parts/Database-connection.php';

// 設定回應的 Content-Type 為 JSON
header('Content-Type: application/json');



# 回應給用戶端的欄位 (格式 JSON)
$output = [
  'success' => false,     // 預設設定為失敗
  'postData' => $_POST,   // 儲存接收到的 POST 資料，用於除錯
  'error' => '',          // 除錯訊息
  'code' => 0,            // 除錯或追踪程式碼
];

// 檢查是否收到了必要的 POST 資料
if (!empty($_POST['amount'])) {
  $isPass = true;

  // TODO: 檢查資料的格式

  $restock_time = null;
  if (!empty($_POST['restock_time'])) {
    // 轉換生日日期格式
    $t = strtotime($_POST['restock_time']); # 取得 timestamp
    if ($t !== false) {
      $restock_time = date('Y-m-d', $t);
    }
  }

  // 如果通過了所有檢查，執行更新資料庫的動作
  if ($isPass) {
    # 避免 SQL injection
    $sql = "UPDATE `restock` SET 
      `amount`=?,
      `restock_time`=?
    WHERE `id`=?";
    // 使用 PDO 準備 SQL 語句
    $stmt = $pdo->prepare($sql);
    // 執行 SQL 語句並傳入對應的參數
    $stmt->execute([
      $_POST['amount'],
      $restock_time,
      $_POST['id']  // 添加 id
    ]);

    // 更新成功的話，將 success 標記為 true
    $output['success'] = boolval($stmt->rowCount());
  }
}

// 將結果轉為 JSON 格式並輸出
echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
