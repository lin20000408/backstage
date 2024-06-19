<?php
// require __DIR__ .'/parts/admin-required.php';
require __DIR__ . '/../../parts/Database-connection.php';
header('Content-Type: application/json');
# 回應給用戶端的欄位 (格式 JSON)
$output = [
    'success' => false,
    'postData' => $_POST, # 除錯用
    'error' => '',
    'code' => 0, # 除錯或追踪程式碼
];

if (!empty($_POST['id'])) {



    $isPass = true;

    // 匯入sql
    // TODO: 檢查資料的格式
    if (mb_strlen($_POST['stars']) < 1 ) {
        $isPass = false;
        $output['error'] = 'star請填一到五顆';
    }




    if ($isPass) {
        //防止漏洞用prepare
        $sql = "UPDATE `product_reviews` SET 
  `stars`=?,
  `content`=?,
  `photo`=?
  
WHERE `id`=?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['stars'],
            $_POST['content'],
            $_POST['url'],

            $_POST['id'],
        ]);

        $output['success'] = boolval($stmt->rowCount());
    }
}
echo json_encode($output, JSON_UNESCAPED_UNICODE);
