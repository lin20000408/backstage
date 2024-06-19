<?php require __DIR__ . '/../parts/Database-connection.php' ;
include '../add-picture.php' ;  ?><!-- 引入資料庫連線設定 -->


<?php
$title = '新增進貨單';  // 頁面標題
$pageName = 'restock-add';  // 頁面名稱

// 查詢產品名稱，這裡使用了 JOIN 來從 product_variants 表中獲取對應的產品名稱
$query = "
    SELECT p.name
    FROM products p
    INNER JOIN product_variants pv ON p.id = pv.product_id
";
$stmt = $pdo->query($query);
$productNames = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>


<?php include __DIR__ . '/../parts/html-head.php' ?><!-- 引入 HTML 頁面的 head 部分 -->

<style>
   /* 下一個元素的 style 設定 */
  /* nextElementSibling的style */
  form .mb-3 .form-text {
    color: red;
  }
</style>

<div class="d-flex w-100 h-100">

  <?php include __DIR__ . '/../parts/html-main.php' ?><!-- 引入主要內容區塊 -->

  <?php include __DIR__ . '/restock-parts/restock-navbar.php' ?><!-- 引入進貨單的導覽列 -->

  <div class="container">
    <div class="row">
      <div class="col-6">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">新增進貨單</h5>
            <form name="form1" onsubmit="sendData(event)">

              <div class="mb-3">
                <label for="product_name" class="form-label">產品名稱</label>

                <select id="product_name" name="product_name">
                  <option value="">--請選擇--</option><!-- 這是空職默認選項 -->
                  <?php foreach ($productNames as $productName) : ?>
                    <option value="<?php echo $productName; ?>">
                      <?php echo $productName; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="form-text"></div>
              </div>

              <div class="mb-3">
                <label for="amount" class="form-label">數量</label>
                <input type="text" class="form-control" id="amount" name="amount">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="restock_time" class="form-label">進貨時間</label>
                <input type="date" class="form-control" id="restock_time" name="restock_time">
                <div class="form-text"></div>
              </div>

              <button type="submit" class="btn btn-primary">送出</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 彈出式視窗 -->
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
          <a href="restock.php" class="btn btn-primary">跳到列表頁</a>
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
          <a href="restock.php" class="btn btn-primary">跳到列表頁</a>
        </div>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../parts/html-js.php' ?><!-- 引入 HTML 頁面的 JavaScript 部分 -->
  <script>
    // 取得表單中各個欄位的元素
    const {
      product_name: product_nameField,
      amount: amountField,
      restock_time: restock_timeField,
    } = document.form1;

    function sendData(e) {
      // 欄位的外觀要回復原來的狀態
      product_nameField.style.border = "1px solid #CCC";
      product_nameField.nextElementSibling.innerHTML = '';
      amountField.style.border = "1px solid #CCC";
      amountField.nextElementSibling.innerHTML = '';
      restock_timeField.border = "1px solid #CCC";
      restock_timeField.nextElementSibling.innerHTML = '';


      e.preventDefault(); // 不要讓有外觀的表單以傳統的方式送出

      let isPass = true; // 有沒有通過檢查, 預設值為 true

      // TODO: 檢查資料的格式

      // 產品名稱是必填 !代表相反
      if (!product_nameField.value) {
        isPass = false;
        product_nameField.style.border = "2px solid red";
        product_nameField.nextElementSibling.innerHTML = '請輸入產品名稱';
      }

      // 數量是必填 !代表相反
      // 檢查數量是否為空
      if (!amountField.value) {
        isPass = false;
        amountField.style.border = "2px solid red";
        amountField.nextElementSibling.innerHTML = '請輸入數量';
      }

      // 進貨時間是必填 !代表相反
      // 檢查數量是否為空
      if (!restock_timeField.value) {
        isPass = false;
        restock_timeField.style.border = "2px solid red";
        restock_timeField.nextElementSibling.innerHTML = '請輸入進貨時間';
      }



      // 如果欄位都有通過檢查, 才要發 AJAX
      if (isPass) {
        const fd = new FormData(document.form1); // 看成沒有外觀的表單

        fetch('./restock-api/restock-add-api.php', {
            method: 'POST',
            body: fd
          })
          .then(r => r.json())
          .then(result => {
            console.log(result);
            if (result.success) {
              // alert('資料新增成功')
              successModal.show();
            } else {
              // alert('資料新增失敗')
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