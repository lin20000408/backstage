<?php 
require __DIR__ . '/../admin/required-admin.php';
require __DIR__ . '/../parts/Database-connection.php';
$title = '新增管理者基本資料';
$pageName = 'admin-';
$navbarName ='addAdmin';
$isAdminPage = 'admin';

// $id = isset ($_SESSION['admin']['id']) ? $_SESSION['admin']['id'] : null;

// //管理者資訊
// $row = $pdo->query("SELECT * FROM `midterm`.admin WHERE id=$id")->fetch();

?>
<?php include __DIR__ . '/../add-picture.php'?>
<?php include __DIR__ . '/../parts/html-head.php' ?>

<style>
  form .mb-3 .form-text {
    color: red;
  }

  #add-mid{
    display:flex;
    justify-content: center;
  }
</style>
<div class="d-flex w-100 h-100">

  <?php include __DIR__ . '/../parts/html-main.php' ?>

  <?php include __DIR__ . '/admin-parts/admin-navbar.php' ?>

  <div class="container">
    <div class="row" id="add-mid">
      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">新增通訊錄</h5>
            <form name="form1" onsubmit="sendData(event)">
              <div class="mb-3">
                <label for="adminName" class="form-label">姓名</label>
                <input type="text" class="form-control" id="adminName" name="adminName">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="account" class="form-label">帳號</label>
                <input type="text" class="form-control" id="account" name="account">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">密碼</label>
                <input type="text" class="form-control" id="password" name="password">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">信箱</label>
                <input type="text" class="form-control" id="email" name="email">
                <div class="form-text"></div>
              </div>
              <button type="submit" class="btn btn-primary">提交</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">資料沒有新增</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          資料新增沒有成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
        <a href="admin.php" class="btn btn-primary">跳到列表頁</a>
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
          <h1 class="modal-title fs-5">資料新增結果</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success" role="alert">
            資料新增成功
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
        <a href="admin.php" class="btn btn-primary">跳到列表頁</a>          
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
      fetch('./admin-api/addAdmin-api.php', {
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