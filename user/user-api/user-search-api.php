<?php
require __DIR__ . '../../parts/Database-connection.php';

// 獲取搜尋名稱
$search_name = isset($_GET['account']) ? $_GET['account'] : '';
$rows = [];

if (isset($_GET['status'])) {
  $status = $_GET['status'];
  if ($status == 'both') {
    $search_both = '未使用';
    $sql = "SELECT * FROM `midterm`.user 
            JOIN coupon_send_management 
            ON user.id = coupon_send_management.user_id
            WHERE account LIKE ? AND usage_status IN (?, '已使用')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search_name%", $search_both]);
    $rows = $stmt->fetchAll();
  } else {
    $sql = "SELECT * FROM `midterm`.user 
            JOIN coupon_send_management 
            ON user.id = coupon_send_management.user_id
            WHERE account LIKE ? AND usage_status = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search_name%", $status]);
    $rows = $stmt->fetchAll();
  }
} else {
  echo "未收到 status 參數";
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="" />
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors" />
  <meta name="generator" content="Hugo 0.83.1" />
  <title>會員搜尋</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sidebars/" />
  <!-- Bootstrap core CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Custom styles for this template -->
  <style>
    .user {
      color: blue;
      text-align: center;
      padding: 15px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="user">會員篩選結果</h2>
    <div class="class">
      <div class="col">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>name</th>
              <th>account</th>
              <th>email</th>
              <th>address</th>
              <th>coupon_name</th>
              <th>usage_status</th>
              <th>usage_time</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlentities($r['name']) ?></td>
                <td><?= htmlentities($r['account']) ?></td>
                <td><?= htmlentities($r['email']) ?></td>
                <td><?= htmlentities($r['address']) ?></td>
                <td><?= htmlentities($r['coupon_name']) ?></td>
                <td><?= htmlentities($r['usage_status']) ?></td>
                <td><?= htmlentities($r['usage_time']) ?></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>