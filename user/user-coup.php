<?php
require __DIR__ . '/../parts/Database-connection.php';
$title = '會員管理者列表';
$pageName = 'user-';
$navbarName = 'coupon';
$id = isset ($_SESSION['admin']['id']) ? $_SESSION['admin']['id'] : null;
//管理者資訊
$row = $pdo->query("SELECT * FROM `midterm`.admin WHERE id=$id")->fetch();
// 設定每頁顯示的資料筆數
$per_page = 10;

// 獲取當前頁碼，默認為第1頁
$current_page = isset ($_GET['page']) ? intval($_GET['page']) : 1;

// 計算資料表中的總資料筆數
$total_count = $pdo->query("SELECT COUNT(*) FROM `midterm`.user JOIN coupon_send_management ON user.id = coupon_send_management.user_id")->fetchColumn();

// 計算總頁數
$total_pages = ceil($total_count / $per_page);

// 確保當前頁碼不超出範圍
$current_page = max(1, min($current_page, $total_pages));

// 計算 LIMIT 子句中的偏移量
$offset = ($current_page - 1) * $per_page;



// 準備 SQL 查詢，並添加 LIMIT 子句以實現分頁
$sql = "SELECT * FROM `midterm`.user 
        JOIN coupon_send_management 
        ON user.id = coupon_send_management.user_id
        ORDER BY user.id DESC
        LIMIT $offset, $per_page";
$rows = $pdo->query($sql)->fetchAll();

?>

<style>
  .user {
    color: blue;
    text-align: center;
  }

  nav.navbar .nav-item .nav-link.active {
    border-radius: 10px;
    background-color: #0d6efd;
    color: white;
    font-weight: 800;
  }

  #right-content {
    margin-left: 450px;
    position: absolute;
  }

  #left-corner {
    position: absolute;
    z-index: 3;
  }
</style>

<?php include __DIR__ . '/../parts/html-head.php' ?>
<div class="d-flex w-100 h-100">
  <?php include __DIR__ . '/user-html-main01.php' ?>
  <nav class="w-75" id="right-content">
    <div class="container">
      <nav class="navbar navbar-expand-lg bg-body-tertiary shadow p-3 mb-5 bg-body rounded">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="user.php">會員管理</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $navbarName == 'list' ? 'active' : '' ?>" href="user.php">基本資料</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $navbarName == 'coupon' ? 'active' : '' ?>" href="user-coup.php">優惠券</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $navbarName == 'chart' ? 'active' : '' ?>" href="user-chart.php">統計圖表</a>
              </li>
            </ul>
            <form class="d-flex ms-auto" role="search" method="GET" action="./user-api/user-coupon-api.php" name="form1"
              onsubmit="sendData(event)" style="margin-top:10px;">
              <select class="form-select me-2" aria-label="Status" name="status">
                <option value="both">優惠券使用+未使用</option>
                <option value="已使用">優惠券已使用</option>
                <option value="未使用">優惠券未使用</option>
              </select>
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="account" />
              <button class="btn btn-outline-success" type="submit">
                Search
              </button>
            </form>
          </div>
        </div>
      </nav>
    </div>
    <div class="container">
      <h2 class="user">會員優惠券</h2>
      <div class="class">
        <div class="col">
          <table class="table table-bordered table-striped" id="table">
            <thead>
              <tr>
                <th>會員編號</th>
                <th>姓名</th>
                <th>帳號</th>
                <th>信箱</th>
                <th>地址</th>
                <th>優惠券名稱</th>
                <th>使用狀況</th>
                <th>使用時間</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <td>
                    <?= $r['id'] ?>
                  </td>
                  <td>
                    <?= htmlentities($r['name']) ?>
                  </td>
                  <td>
                    <?= htmlentities($r['account']) ?>
                  </td>
                  <td>
                    <?= htmlentities($r['email']) ?>
                  </td>
                  <td>
                    <?= htmlentities($r['address']) ?>
                  </td>
                  <td>
                    <?= htmlentities($r['coupon_name']) ?>
                  </td>
                  <td>
                    <?= htmlentities($r['usage_status']) ?>
                  </td>
                  <td>
                    <?= htmlentities($r['usage_time']) ?>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- 分頁按鈕 -->
  </nav>
</div>
<?php include __DIR__ . '/../parts/html-js.php' ?>


<script>
  const body = document.querySelector('tbody');
  const table = document.getElementById('table');

  function sendData(e) {
    e.preventDefault();
    let isPass = true;
    if (isPass) {
      const formData = new FormData(document.form1);
      fetch('./user-api/user-coupon-api.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          console.log(data);
          // 清空现有表格数据
          body.innerHTML = '';
          if (data) {
            data.forEach(row => {
              const tr = document.createElement('tr');
              for (const key in row) {
                const td = document.createElement('td');
                td.textContent = row[key];
                tr.appendChild(td);
              }
              body.appendChild(tr);
            })
          }
          console.log(body);
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  }
</script>

<?php include __DIR__ . '/../parts/html-footer.php' ?>