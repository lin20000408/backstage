<?php 
require __DIR__ . '/required-admin.php';
require __DIR__ . '/../parts/Database-connection.php';
$title = '編輯管理者資料';
$pageName = 'edit';
$isAdminPage = 'admin';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (empty($id)) {
  header('Location: admin.php');
  exit;
}
$r = $pdo->query("SELECT * FROM `midterm`.admin WHERE id=$id")->fetch();
if (empty($r)) {
  header('Location: admin.php');
  exit;
}

// 更改圖片
$ids = isset ($_SESSION['admin']['id']) ? $_SESSION['admin']['id'] : 0;
//管理者資訊
$row = $pdo->query("SELECT * FROM `midterm`.admin WHERE id=$ids")->fetch();

?>


<?php include __DIR__ . '/../parts/html-head.php' ?>
<style>
  form .mb-3 .form-text {
    color: red;
  }
</style>
<div class="d-flex w-100 h-100">

  <?php include __DIR__ . '/../parts/html-main.php' ?>

  <?php include __DIR__ . '/admin-parts/admin-navbar.php' ?>

  <div class="container">
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">編輯管理者資料</h5>
            <form name="form1" onsubmit="sendData(event)">

						<input type="hidden" name="id" value="<?= $r['id'] ?>">
            <div class="mb-3">
              <label for="name" class="form-label">編號</label>
              <input type="text" class="form-control" value="<?= $r['id'] ?>" disabled>

            </div>
              <div class="mb-3">
                <label for="adminName" class="form-label">姓名</label>
                <input type="text" class="form-control" id="adminName" name="adminName" value="<?= $r['adminName'] ?>" >
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="account" class="form-label">帳號</label>
                <input type="text" class="form-control" id="account" name="account" value="<?= $r['account'] ?>" >
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">密碼</label>
                <input type="text" class="form-control" id="password" name="password" value="">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">信箱</label>
                <input type="text" class="form-control" id="email" name="email" value="<?= $r['email'] ?>">
                <div class="form-text"></div>
              </div>
              <button type="submit" class="btn btn-primary">修改</button>
            </form>
          </div>
        </div>
      </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">資料修改結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          資料修改成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button>
        <a href="javascript: location.href=document.referrer" class="btn btn-primary">跳到列表頁</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">資料沒有修改</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          資料沒有修改成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button>
        <a href="javascript: location.href=document.referrer" class="btn btn-primary">跳到列表頁</a>
      </div>
    </div>
  </div>
</div>


<?php include __DIR__ . '/../parts/html-js.php' ?>
<script>
  const {
    adminName: nameField,
    email: emailField,
  } = document.form1;

  const password = document.querySelector('#password');


  function validateEmail(email) {
    const re =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

  function sendData(e) {
    // 欄位的外觀要回復原來的狀態
    nameField.style.border = "1px solid #CCC";
    nameField.nextElementSibling.innerHTML = '';
    emailField.style.border = "1px solid #CCC";
    emailField.nextElementSibling.innerHTML = '';

    e.preventDefault(); // 不要讓有外觀的表單以傳統的方式送出
    let isPass = true; // 有沒有通過檢查, 預設值為 true
    // 姓名是必填, 長度要 2 以上
    if (nameField.value.length < 2) {
      isPass = false;
      nameField.style.border = "2px solid red";
      nameField.nextElementSibling.innerHTML = '請輸入正確的名字';
    }
    // email 若有填才檢查格式, 沒填不檢查格式
    if (emailField.value && !validateEmail(emailField.value)) {
      isPass = false;
      emailField.style.border = "2px solid red";
      emailField.nextElementSibling.innerHTML = '請輸入正確的 Email';
    }



    // 如果欄位都有通過檢查, 才要發 AJAX
    if (isPass) {
      const fd = new FormData(document.form1); // 看成沒有外觀的表單
      fetch('./admin-api/editAdmin-api.php', {
        method: 'POST',
        body: fd
      })
        .then(r => r.json())
        .then(result => {
          console.log(result);
          if (result.success) {
            successModal.show();
          } else {
            if (result.error) {
              failureInfo.innerHTML = result.error;
            } else {
              failureInfo.innerHTML = '資料新增沒有成功';
            }
            failureModal.show();
          }
        })
        .catch(ex => {
          console.log(ex);
           // alert('資料新增發生錯誤' + ex)
          failureInfo.innerHTML = '資料新增發生錯誤' + ex;
          failureModal.show();
        })

    }
  }
  const successModal = new bootstrap.Modal('#successModal');
  const failureModal = new bootstrap.Modal('#failureModal');
  const failureInfo = document.querySelector('#failureModal .alert-danger');
</script>

<?php include __DIR__ . '/../parts/html-footer.php' ?>