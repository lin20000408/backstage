    <?php require '.././parts/Database-connection.php';
    $title = '編輯訂單';
    $pageName = 'order-';
    include __DIR__ . '/../add-picture.php';
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $couponID = $pdo->query("SELECT `order`.`coupon_id` fROM `order` where id = $id")->fetchColumn();
    $stmt = $pdo->prepare("SELECT `user_id` FROM `order_details` JOIN `order` ON `order_details`.`order_id` = `order`.`id` WHERE `order_id` = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $userID = $stmt->fetchColumn();

    if (empty($id)) {
      header('Location: order-list.php');
      exit;
    }
    // 如果有優惠券執行第一段，沒有執行第二段
    if (!empty($couponID)) {
      $r = $pdo->query("SELECT  DISTINCT order_details.*, `order`.*,coupon.id as coupon_id,name,money, coupon_send_management.user_id
    FROM `midterm`.order_details 
    INNER JOIN `midterm`.order 
    ON order_details.order_id = order.id
    JOIN `midterm`.coupon 
    on order.coupon_id = coupon.id
    JOIN `midterm`.coupon_send_management
    on coupon.id = coupon_send_management.coupon_id where order_id = $id and coupon_send_management.user_id = $userID ")->fetchAll();
    } else $r = $pdo->query("SELECT order_details.*,`order`.*
    FROM `midterm`.order_details 
    INNER JOIN `midterm`.order 
    ON order_details.order_id = order.id
    where order_id = $id")->fetchAll();
    if (empty($r)) {
      header('Location: order-list.php');
      exit;
    }


    // 取得商品分類及商品名稱的數據
    //products_main_type查詢
    $productSql = 'SELECT * from `midterm`.product_main_types';
    $productStmt = $pdo->query($productSql);
    $productRows = $productStmt->fetchAll(PDO::FETCH_NUM);

    //products 查詢
    $productSql2 = 'SELECT * from `midterm`.products';
    $productStmt2 = $pdo->query($productSql2);
    $productRows2 = $productStmt2->fetchAll(PDO::FETCH_NUM);

    // users id name address 查詢
    $productSql3  = 'SELECT user.id,user.name,user.address,user.phone FROM `midterm`.user';
    $productStmt3 = $pdo->query($productSql3);
    $productRows3 = $productStmt3->fetchAll(PDO::FETCH_NUM);

    // 優惠券查詢發送的相關會員id,優惠券id,名稱,折扣金額
    $productSql4  = 'SELECT coupon_send_management.user_id,coupon_send_management.coupon_id,coupon_send_management.coupon_name,coupon.money FROM `midterm`.coupon join coupon_send_management on coupon.id = coupon_send_management.coupon_id';
    $productStmt4 = $pdo->query($productSql4);
    $productRows4 = $productStmt4->fetchAll(PDO::FETCH_NUM);
    ?>



    <?php include '.././parts/html-head.php' ?>
    <style>
      form .mb-3 .form-text {
        color: red;
      }

      /* 自定義商品數量、價格、總價、評價格子的大小 */
      input.product_amount,
      input.price,
      input.productTotalPrices,
      input.product_review_id {
        width: 100px;
      }

      .product-select {
        align-items: center;
      }
    </style>
    <div class="d-flex w-100 h-100">

      <?php include  './order-parts/order-html-main.php' ?>
      <?php include  './order-parts/order-navbar-add-edit.php' ?>
      <div class="container">
        <div class="rpw">
          <div class="col-6 w-100">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">編輯訂單</h5>

                <form name="form1" onsubmit="sendData(event)">
                  <div class="row">
                    <div class="col">
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>商品名稱</th>
                            <th>商品尺寸</th>
                            <th>商品顏色</th>
                            <th>商品數量</th>
                            <th>商品單價</th>
                            <th>商品總價</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($r as $r) : ?>
                            <tr>
                              <td><?= $r['product_name'] ?></td>
                              <td><?= $r['product_size'] ?></td>
                              <td><?= $r['product_color'] ?></td>
                              <td><?= $r['product_amount'] ?></td>
                              <td><?= $r['price'] ?></td>
                              <td><?= $r['product_amount_total'] ?></td>
                            </tr>

                          <?php endforeach ?>

                        </tbody>
                      </table>


                      <div class="prdocuts_field" id="productsField">
                        <!-- 新增商品區塊 -->
                      </div>
                      <div class="mb-3">
                        <!-- 讓欄位資料送出 但不給修改 -->
                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                        <label for="name" class="form-label">訂單編號</label>
                        <input type="text" class="form-control" value="<?= $r['id'] ?>" disabled>
                      </div>
                      <div class="mb-3">
                        <input type="hidden" name="user_id" value="<?= $r['user_id'] ?>">
                        <label for="user_id" class="form-label">會員編號</label>
                        <input type="text" class="form-control" id="user_id" name="user_id" value="<?= $r['user_id'] ?>" disabled>
                        <div class="form-text"></div>
                      </div>
                      <div class="mb-3">
                        <label for="receiver" class="form-label">收件人</label>
                        <input type="text" class="form-control" id="receiver" name="receiver" value="<?= $r['receiver'] ?>">
                        <div class="form-text"></div>
                      </div>
                      <div class="mb-3">
                        <label for="receiver_address" class="form-label">收件人地址</label>
                        <input type="text" class="form-control" id="receiver_address" name="receiver_address" value="<?= $r['receiver_address'] ?>">
                        <div class="form-text"></div>
                      </div>
                      <div class="mb-3">
                        <label for="receiver_phone" class="form-label">收件人電話</label>
                        <input type="text" class="form-control" id="receiver_phone" name="receiver_phone" value="<?= $r['receiver_phone'] ?>">
                        <div class="form-text"></div>
                      </div>
                      <div class="mb-3">
                        <!--  如果有優惠券執行第一段，沒有執行第二段 -->
                        <?php if (!empty($couponID)) : ?>
                          <input type="hidden" class="form-control" id="coupon_id" name="coupon_id" value="<?= $r['coupon_id'] ?>">
                          <label for="coupon_id" class="form-label">優惠券名稱</label>
                          <input type="text" class="form-control" id="coupon_id" name="coupon_id" value="<?= $r['name'] ?>" disabled>
                        <?php else : ?>
                          <input type="hidden" class="form-control" id="coupon_id" name="coupon_id" value="">
                          <label for="coupon_id" class="form-label">優惠券名稱</label>
                          <input type="text" class="form-control" id="coupon_id" name="coupon_id" value="未使用" disabled>
                        <?php endif; ?>
                      </div>
                      <div class="mb-3">
                        <!--  如果有優惠券執行第一段，沒有執行第二段 -->
                        <?php if (!empty($couponID)) : ?>
                          <input type="hidden" name="coupon_discount" value=<?= $r['money'] ?>>
                          <label for="coupon_discount" class="form-label">優惠券折扣金額</label>
                          <input type="text" class="form-control" id="coupon_discount" name="coupon_discount" value=<?= $r['money'] ?> disabled>
                          <div class="form-text"></div>
                        <?php else : ?>
                          <input type="hidden" name="coupon_discount" value=0>
                          <label for="coupon_discount" class="form-label">優惠券折扣金額</label>
                          <input type="text" class="form-control" id="coupon_discount" name="coupon_discount" value=0 disabled>
                          <div class="form-text"></div>
                        <?php endif; ?>
                      </div>
                      <div class="mb-3">
                        <label for="shipping_method" class="form-label">運送方式</label>
                        <select class="form-select" id="shipping_method" name="shipping_method" value="<?= $r['shipping_method'] ?>">
                          <option value="宅配到家" <?= ($r['shipping_method'] == '宅配到家') ? 'selected' : '' ?>>宅配到家</option>
                          <option value="超商取貨" <?= ($r['shipping_method'] == '超商取貨') ? 'selected' : '' ?>>超商取貨</option>
                        </select>
                      </div>

                      <div class="mb-3">
                        <label for="payment_method" class="form-label">付款方式</label>
                        <select class="form-select" id="payment_method" name="payment_method" value="<?= $r['payment_method'] ?>">
                          <option value="信用卡" <?= ($r['payment_method'] == '信用卡') ? 'selected' : '' ?>>信用卡</option>
                          <option value="貨到付款" <?= ($r['payment_method'] == '貨到付款') ? 'selected' : '' ?>>貨到付款</option>
                        </select>
                      </div>

                      <div class="mb-3">
                        <input type="hidden" name="total_cost" value="<?= $r['total_cost'] ?>">
                        <label for="total_cost" class="form-label">總金額</label>
                        <input type="text" class="form-control" id="total_cost" name="total_cost" value="<?= $r['total_cost'] ?>" disabled>
                        <div class="form-text"></div>
                      </div>

                      <div class="mb-3">
                        <label for="order_status" class="form-label">訂單狀態</label>
                        <select class="form-select" id="order_status" name="order_status">
                          <option value="未完成" <?= ($r['order_status'] == '未完成') ? 'selected' : '' ?>>未完成</option>
                          <option value="已完成" <?= ($r['order_status'] == '已完成') ? 'selected' : '' ?>>已完成</option>
                          <option value="退貨/退款處理中" <?= ($r['order_status'] == '退貨/退款處理中') ? 'selected' : '' ?>>退貨/退款處理中</option>
                        </select>
                      </div>


                      <button type="submit" class="btn btn-primary">修改訂單</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5">訂單修改結果</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="alert alert-info" role="alert">
                訂單修改成功
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改訂單</button>
              <a href="order-list.php" class="btn btn-primary">跳到訂單列表</a>
            </div>
          </div>
        </div>
      </div>
      </nav>
    </div>

    <div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5">訂單修改結果</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger" role="alert">
              訂單修改失敗
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改訂單</button>
            <a href="order-list.php" class="btn btn-primary">跳到訂單列表</a>
          </div>
        </div>
      </div>
    </div>
    </nav>
    </div>

    <?php include  '.././parts/html-js.php' ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      const {
        receiver: receiverField,
        receiver_address: receiverAddressField,
      } = document.form1


      function sendData(e) {
        // 欄位外觀要回復原來狀態
        receiverField.style.border = '1px solid #ccc'
        receiverField.nextElementSibling.innerHTML = ''
        receiverAddressField.style.border = '1px solid #ccc'
        receiverAddressField.nextElementSibling.innerHTML = ''
        e.preventDefault(); //不要讓表單以傳統方式送出

        let isPass = true; // 有沒有通過檢查，預設值為 true
        // TODO:檢查資料格式

        if (receiverField.value.length < 1) {
          isPass = false
          receiverField.style.border = '2px solid red'
          receiverField.nextElementSibling.innerHTML = '請輸入收件人'
        }

        if (receiverAddressField.value.length < 2) {
          isPass = false
          receiverAddressField.style.border = '2px solid red'
          receiverAddressField.nextElementSibling.innerHTML = '請輸入收件人地址'
        }

        if (isPass) {
          const fd = new FormData(document.form1); // 沒有外觀的表單，讓前端的表單複製資料給這份沒有外觀表單，把資料向後端丟
          fetch('./order-api./order-edit-api.php', {
              method: 'POST',
              body: fd
            }).then(r => r.json())
            .then(result => {

              console.log(result);
              if (result.success) {
                // 資料新增成功
                successModal.show();
              } else {
                // 資料新增失敗
                if (result.error) {
                  failureInfo.innerHTML = result.error
                } else {
                  failureInfo.innerHTML = '訂單修改失敗'
                }
                failureModal.show();
              }
            })
            .catch(ex => {
              console.log(ex);
              failureInfo.innerHTML = '訂單修改失敗' + ex
              failureModal.show();
            })
        }

      }

      const successModal = new bootstrap.Modal('#successModal')
      const failureModal = new bootstrap.Modal('#failureModal')
      const failureInfo = document.querySelector('#failureModal .alert-danger');
    </script>
    <?php include  '.././parts/html-footer.php' ?>