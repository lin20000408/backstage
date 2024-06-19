<?php require __DIR__ . '/../parts/Database-connection.php' ?>

<?php
//放 sql 查詢
// $rows = $pdo->query("SELECT * FROM `midterm`.order")->fetchAll();
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>

<div class="d-flex w-100 h-100">

  <?php include __DIR__ . '/../parts/html-main.php' ?>

  <?php include __DIR__ . '/admin-parts/admin-navbar.php' ?>

  <div class="container">
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">登入管理員</h5>
            <form name="form1" onsubmit="sendData(event)">
              <div class="mb-3">
                <label for="account" class="form-label">帳號</label>
                <input type="text" class="form-control" id="account" name="account">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">密碼</label>
                <input type="password" class="form-control" id="password" name="password">
                <div class="form-text"></div>
              </div>
              <button type="submit" class="btn btn-primary">登入</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">登入失敗</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          帳號或密碼錯誤
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續登入</button>
      </div>
    </div>
  </div>
</div>
</div>
<script>
  const {
    account: accountField,
    password:passwordField
  } = document.form1;
  function validateEmail(email) {
    const re =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }
  function sendData(e) {
    // 欄位的外觀要回復原來的狀態
    e.preventDefault(); // 不要讓有外觀的表單以傳統的方式送出
    let isPass = true; // 有沒有通過檢查, 預設值為 true
    // TODO: 檢查資料的格式
    // 姓名是必填, 長度要 2 以上
    // email 若有填才檢查格式, 沒填不檢查格式

    // 如果欄位都有通過檢查, 才要發 AJAX
    if (isPass) {
      const fd = new FormData(document.form1); // 看成沒有外觀的表單
      fetch('./admin-api/loginAdmin-api.php', {
          method: 'POST',
          body: fd
        })
        .then(r => r.json())
        .then(result => {
          console.log(result);
          if (result.success) {
            location.href = 'index-.php';
          } else {
            failureModal.show();
          }
        })
        .catch(ex => {
          console.log(ex);
        })
    }
  }
  const failureModal = new bootstrap.Modal('#failureModal');
</script>

<?php include __DIR__ . '/../parts/html-js.php' ?>


<?php include __DIR__ . '/../parts/html-footer.php' ?>