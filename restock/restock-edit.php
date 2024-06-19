<?php
// 引入資料庫連線檔案
require __DIR__ . '/../parts/Database-connection.php';
include '../add-picture.php';
// 設定網頁標題與頁面名稱
$title = '編輯進貨單';
$pageName = 'restock-edit';

// 取得進貨單編號
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (empty($id)) {
  // 如果編號不存在，重新導向至進貨單列表頁面
  header('Location: restock.php');
  exit;
}

// 從資料庫中取得特定編號的進貨單資料
$r = $pdo->query("SELECT * FROM restock WHERE id=$id")->fetch();
if (empty($r)) {
  // 如果找不到進貨單資料，重新導向至進貨單列表頁面
  header('Location: restock.php');
  exit;
}

?>
<?php include __DIR__ . '/../parts/html-head.php' ?>
<style>
  /* 下一個元素的 style 設定 */
  /* nextElementSibling的style */
  form .mb-3 .form-text {
    color: red;
  }
</style>
<div class="d-flex w-100 h-100">

  <?php include __DIR__ . '/../parts/html-main.php' ?>

  <?php include __DIR__ . '/restock-parts/restock-navbar.php' ?>


  <div class="container">
    <div class="row">
      <div class="col-6">
        <div class="card">

          <div class="card-body">
            <h5 class="card-title">編輯進貨單</h5>

            <!-- 表單開始 -->
            <form name="form1" onsubmit="sendData(event)">
              <!-- 進貨單編號，設定為不可編輯（disabled） -->
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <div class="mb-3">
                <label for="name" class="form-label">編號</label>
                <input type="text" class="form-control" value="<?= $r['id'] ?>" disabled>
              </div>


              <!-- 進貨單編號，設定為不可編輯（disabled） -->
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <div class="mb-3">
                <label for="product_name" class="form-label">產品名稱</label>
                <input type="text" class="form-control" value="<?= $r['product_name'] ?>" disabled>
              </div>


              <div class="mb-3">
                <label for="amount" class="form-label">進貨數量</label>
                <!-- 進貨數量輸入欄位 -->
                <input type="text" class="form-control" id="amount" name="amount" value="<?= $r['amount'] ?>">
                <div class="form-text"></div>
              </div>

              <div class="mb-3">
                <label for="restock_time" class="form-label">進貨時間</label>
                <!-- 進貨時間輸入欄位 -->
                <input type="date" class="form-control" id="restock_time" name="restock_time" value="<?= $r['restock_time'] ?>">
                <div class="form-text"></div>
              </div>




              <!-- 修改按鈕 -->
              <button type="submit" class="btn btn-primary">修改</button>
            </form>
            <!-- 表單結束 -->

          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- 成功修改提示 Modal -->
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
          <!-- 繼續修改按鈕 -->
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button>
          <!-- 跳轉到列表頁按鈕 -->
          <a href="javascript: location.href=document.referrer" class="btn btn-primary">跳到列表頁</a>
        </div>
      </div>
    </div>
  </div>

  <!-- 失敗修改提示 Modal -->
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
          <!-- 繼續修改按鈕 -->
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button>
          <!-- 跳轉到列表頁按鈕 -->
          <a href="javascript: location.href=document.referrer" class="btn btn-primary">跳到列表頁</a>
        </div>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../parts/html-js.php' ?>
  <script>
    // 取得表單中的欄位
    const {
      amount: amountField,
      restock_time: restock_timeField,
    } = document.form1;


    // 發送表單資料的函式
    function sendData(e) {
      // 欄位的外觀要回復原來的狀態
      amountField.style.border = "1px solid #CCC";
      amountField.nextElementSibling.innerHTML = '';
      restock_timeField.style.border = "1px solid #CCC";
      restock_timeField.nextElementSibling.innerHTML = '';


      e.preventDefault(); // 不要讓有外觀的表單以傳統的方式送出

      let isPass = true; // 有沒有通過檢查, 預設值為 true

      // TODO: 檢查資料的格式

      // 數量是必填 !代表相反
      if (!amountField.value) {
        isPass = false;
        amountField.style.border = "2px solid red";
        amountField.nextElementSibling.innerHTML = '請輸入數量';
      }

      // 進貨時間是必填 !代表相反
      if (!restock_timeField.value) {
        isPass = false;
        restock_timeField.style.border = "2px solid red";
        restock_timeField.nextElementSibling.innerHTML = '請輸入進貨時間';
      }

      // 如果欄位都有通過檢查, 才要發 AJAX
      if (isPass) {
        const fd = new FormData(document.form1); // 看成沒有外觀的表單

        fetch('./restock-api/restock-edit-api.php', {
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
                failureInfo.innerHTML = '資料沒有修改成功';
              }
              failureModal.show(); // 顯示成功修改的提示 Modal
            }
          })
          .catch(ex => {
            console.log(ex);
            // alert('資料新增發生錯誤' + ex)
            failureInfo.innerHTML = '資料修改發生錯誤' + ex;
            failureModal.show(); // 顯示修改錯誤的提示 Modal
          })
      }
    }

    // 初始化成功和失敗的提示 Modal
    const successModal = new bootstrap.Modal('#successModal');
    const failureModal = new bootstrap.Modal('#failureModal');
    const failureInfo = document.querySelector('#failureModal .alert-danger');
  </script>
  <?php include __DIR__ . '/../parts/html-footer.php' ?>



