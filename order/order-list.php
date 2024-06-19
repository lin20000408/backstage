    <?php require '.././parts/Database-connection.php';
    $title = '訂單列表';
    $pageName = 'order-';
    $navbarName = 'orderList';
    include __DIR__ . '/../add-picture.php';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    if ($page < 1) {
      header('Location: ?page=1');
      exit;
    }


    $perPage = 10;
    $totalSql = "SELECT COUNT(1) FROM `midterm`.order";
    $totalStmt = $pdo->query($totalSql);
    $totalRows = $totalStmt->fetch(PDO::FETCH_NUM)[0];
    $totalPage = ceil($totalRows / $perPage); #總頁數
    $rows = []; #預設為空陣列
    if ($totalRows > 0) {
      #有資料時，才往下進行
      if ($page > $totalPage) {
        header('Location: ?page=' . $totalPage);
        exit;
      }

      #取得分頁資料
      $sql = sprintf("SELECT * FROM `midterm`.order ORDER BY id DESC LIMIT %s,%s", ($page - 1) * $perPage, $perPage);
      $rows = $pdo->query($sql)->fetchAll();
    }

    ?>

    <?php include '.././parts/html-head.php' ?>

    <div class="d-flex w-100 h-100">

      <?php include '.././parts/html-main.php' ?>
      <?php include './order-parts/order-navbar.php' ?>
      <div class="container text-center">
        <!-- 搜尋按鈕 -->

        <form id="searchForm" class="d-flex justify-content-end" role="search" style="margin-bottom: 50px" name="formSearch" onsubmit="sendData(event)">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <select class="form-control" id="categorySelect" name="category">
                  <option value="id">訂單編號</option>
                  <option value="receiver">收件人</option>
                  <option value="receiver_phone">收件人電話</option>
                </select>
              </div>
            </div>
            <div class="col-md-5">
              <input class="form-control me-2 searchInput" type="search" name="searchInput" placeholder="Search" aria-label="Search" />
            </div>
            <div class="col-md-3" style="padding: 0;">
              <button class="btn btn-outline-success btn-block" type="submit">Search</button>
            </div>
          </div>
        </form>
        <!-- 返回列表按鈕 -->
        <div class="d-none justify-content-end backListBtn"><a class="btn btn-info mb-3" href="order-list.php" role="button"><b style="color: white;">返回總列表</b></a></div>

        <div class="row">
          <div class="col">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th><i class="fa-solid fa-pen-to-square" style=" color:black"></i></a></th>
                  <th>訂單編號</th>
                  <th>收件人</th>
                  <th>收件人電話</th>
                  <th>運送方式</th>
                  <th>付款方式</th>
                  <th>總金額</th>
                  <th>訂單狀態</th>
                  <th>訂單創立時間</th>
                  <th>訂單明細</th>
                  <th><i class="fa-solid fa-trash"></i></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $r) : ?>
                  <tr>
                    <td><a href="order-edit.php?id=<?= $r['id'] ?>"><i class="fa-solid fa-pen-to-square"></i></a></td>
                    <td><?= $r['id'] ?></td>
                    <td><?= $r['receiver'] ?></td>
                    <td><?= $r['receiver_phone'] ?></td>
                    <td><?= $r['shipping_method'] ?></td>
                    <td><?= $r['payment_method'] ?></td>
                    <td><?= $r['total_cost'] ?></td>
                    <td><?= $r['order_status'] ?></td>
                    <td><?= $r['order_creation_time'] ?></td>
                    <td><a href="order-details.php?id=<?= $r['id'] ?>"><i class="fa-solid fa-table-list"></i></a></td>
                    <td><a href="javascript: deleteOne(<?= $r['id'] ?>)"><i class="fa-solid fa-trash"></i></a></td>
                  </tr>

                <?php endforeach ?>

              </tbody>
            </table>
            <div class="row page">
              <div class="col d-flex justify-content-center">
                <nav aria-label="Page navigation example">
                  <ul class="pagination ">
                    <?php for ($i = $page - 3; $i <= $page + 3; $i++) : ?>
                      <?php if ($i >= 1 and $i <= $totalPage) : ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                      <?php endif ?>
                    <?php endfor ?>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- <pre><?= json_encode($rows, JSON_UNESCAPED_UNICODE) ?></pre> -->
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


      function sendData(e) {

        e.preventDefault(); //不要讓表單以傳統方式送出
        let searchInput = document.querySelector('input[name="searchInput"]').value
        const fd = new FormData(document.formSearch); // 沒有外觀的表單，讓前端的表單複製資料給這份沒有外觀表單，把資料向後端丟
        fd.append("search", searchInput)
        fetch("../order/order-api/order-search-api.php", {
            method: 'POST',
            body: fd
          }).then(r => r.json())
          .then(result => {
            console.log(result);
            if (result) {
              // 有資料的話隱藏分頁欄位，以及秀出返回按鈕
              const backListBtn = document.querySelector('.backListBtn')
              backListBtn.classList.remove("d-none")
              backListBtn.classList.add("d-flex")
              const pagenation = document.querySelector('.page')
              pagenation.classList.add('visually-hidden'); // 添加 visually-hidden 隐藏元素    
              result.forEach(product => {
                // 填入拿回來的資料
                console.log(123);
                let tBody = document.querySelector('tbody');
                tBody.innerHTML = '';
                result.forEach(product => {
                  tBody.innerHTML += `<tr>
                    <td><a href="order-edit.php?id=${product.id}"><i class="fa-solid fa-pen-to-square"></i></a></td>
                    <td>${product.id}</td>
                    <td>${product.receiver}</td>
                    <td>${product.receiver_phone}</td>
                    <td>${product.shipping_method}</td>
                    <td>${product.payment_method}</td>
                    <td>${product.total_cost}</td>
                    <td>${product.order_status}</td>
                    <td>${product.order_creation_time}</td>
                    <td><a href="order-details.php?id=${product.id}"><i class="fa-solid fa-table-list"></i></a></td>
                    <td><a href="javascript: deleteOne(${product.id})"><i class="fa-solid fa-trash"></i></a></td>
                </tr>`;
                });
              });
            }
          })
          .catch(ex => {
            console.log(ex);
            alert('未找到任何資料');
          })

      }
    </script>

    <?php include '.././parts/html-footer.php' ?>