    <?php require '.././parts/Database-connection.php';
    $title = '訂單詳情';
    $pageName = 'order-';
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $couponID = $pdo->query("SELECT `order`.`coupon_id` fROM `order` where id = $id")->fetchColumn();
    $stmt = $pdo->prepare("SELECT `user_id` FROM `order_details` JOIN `order` ON `order_details`.`order_id` = `order`.`id` WHERE `order_id` = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $userID = $stmt->fetchColumn();
    $money = isset($r['money']) ? intval($r['money']) : 0;
    if (empty($id)) {
      header('Location: order-list.php');
      exit;
    }
    // 選擇訂單明細，訂單的優惠券id,以及優惠券的所有資料
    if (!empty($couponID)) {
      $row = $pdo->query("SELECT  DISTINCT order_details.*, order_details.id as order_details_id , `order`.coupon_id ,`order`.total_cost,coupon.*, coupon_send_management.user_id
    FROM `midterm`.order_details 
    INNER JOIN `midterm`.order 
    ON order_details.order_id = `order`.id
    JOIN `midterm`.coupon 
    on `order`.coupon_id = coupon.id
    JOIN `midterm`.coupon_send_management
    on coupon.id = coupon_send_management.coupon_id where order_id = $id and coupon_send_management.user_id = $userID")->fetchAll();
    } else {
      $row = $pdo->query("SELECT order_details.*, order_details.id as order_details_id , `order`.*
    FROM `midterm`.order_details 
    INNER JOIN `midterm`.order 
    ON order_details.order_id = order.id
    where order_id = $id")->fetchAll();
    }
    // 取得收件人資訊
    $row2 = $pdo->query("SELECT order_details.*, order.coupon_id ,order.receiver,order.receiver_phone,order.receiver_address
FROM `midterm`.order_details 
INNER JOIN `midterm`.order 
ON order_details.order_id = order.id where order_id = $id")->fetchAll();
    if (empty($row)) {
      header('Location : order-list.php');
      exit;
    }

    ?>

    <?php include  '.././parts/html-head.php' ?>

    <div class="d-flex w-100 h-100">

      <?php include  '../order/order-parts/order-none-login-picture-html-main.php' ?>
      <?php include  '../order/order-parts/order-navbar.php' ?>
      <div class="container text-center">
        <div class="row">
          <div class="col">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>訂單編號</th>
                  <th>會員編號</th>
                  <th>商品名稱</th>
                  <th>商品尺寸</th>
                  <th>商品顏色</th>
                  <th>商品數量</th>
                  <th>商品單價</th>
                  <th>商品總價</th>
                  <th>新增商品評價</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($row as $r) : ?>
                  <tr>
                    <td><?= $r['order_id'] ?></td>
                    <td><a href="../user/user.php" style="text-decoration: none; font-weight:bold"> <?= $r['user_id'] ?></a></td>
                    <td><?= $r['product_name'] ?></td>
                    <td><?= $r['product_size'] ?></td>
                    <td><?= $r['product_color'] ?></td>
                    <td><?= $r['product_amount'] ?></td>
                    <td><?= $r['price'] ?></td>
                    <td><?= $r['product_amount_total'] ?></td>
                    <?php if (is_null($r['product_reviews_id'])) : ?>
                      <td><a href="../product-reviews/product-review-add.php?id=<?= $r['order_details_id'] ?>"><i class="fa-regular fa-comment"></i></a></td>
                    <?php else : ?>
                      <td>已評論</td>
                    <?php endif; ?>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>

            <div class='d-flex row gap-3 ms-0'>
              <div class="card" style="width: 21rem;">
                <div class="card-body">
                  <?php foreach ($row2 as $r2) : ?>
                  <?php endforeach ?>
                  <h5 class="card-title">收件人資訊</h5>
                  <p class="card-text">姓名:<span><b><?= $r2['receiver'] ?></b></span></p>
                  <p class="card-text">電話:<span><b><?= $r2['receiver_phone'] ?></b></span></p>
                  <p class="card-text">地址:<span><b><?= $r2['receiver_address'] ?></b></span></p>
                </div>
              </div>
              <div class="card" style="width: 20rem;">
                <div class="card-body">

                  <?php if (!empty($couponID)) : ?>
                    <h5 class="card-title">訂單金額</h5>
                    <p class="card-text">優惠券: <span><b><?= $r['name'] ?></b></span></p>
                    <p class="card-text">折抵金額: <span><b><?= $r['money'] ?></b></span></p>
                    <p class="card-text">訂單總金額: <span><b><?= $r['total_cost'] - $money ?></b></span></p>
                  <?php else : ?>
                    <h5 class="card-title">訂單金額</h5>
                    <p class="card-text">優惠券: <b>未使用</b></span></p>
                    <p class="card-text">折抵金額: <span><b>0</b></span></p>
                    <p class="card-text">訂單總金額: <span><b><?= $r['total_cost'] ?></b></span></p>
                  <?php endif; ?>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- <pre><?= json_encode($row, JSON_UNESCAPED_UNICODE) ?></pre> -->
    </div>
    </nav>
    </div>

    <?php include '.././parts/html-js.php' ?>
    <script>
      function deleteOne(id) {
        if (confirm(`是否要刪除訂單為${id}的項目`)) {
          location.href = `order-delete.php?id=${id}`
        }
      }
    </script>

    <?php include '.././parts/html-footer.php' ?>