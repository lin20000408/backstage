<?php
require __DIR__ . '/../required-admin.php';
require  '../../parts/Database-connection.php';
header('Content-Type: application/json');
# 回應給用戶端的欄位 (格式 JSON)
$output = [
  'success' => false,
  'postData' => $_POST, # 除錯用
  'error' => '',
  'code' => 0, # 除錯或追踪程式碼
];
if(! empty($_POST['adminName'])){
	$isPass = true;
	// TODO: 檢查資料的格式
  if (mb_strlen($_POST['adminName']) < 2) {
    $isPass = false;
    $output['error'] = '姓名請填兩個字以上';
  }
	if($isPass){
    $sql = "INSERT INTO `midterm`.admin(
    `adminName`, `account`, `password`, 
    `email`
    ) VALUES (
			?,?,?,?)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute([
				$_POST['adminName'],
				$_POST['account'],
				password_hash($_POST['password'],PASSWORD_DEFAULT),
				$_POST['email'],

			]);
			$output['success'] = boolval($stmt->rowCount());
		}

}
echo json_encode($output, JSON_UNESCAPED_UNICODE);



