<?php

require __DIR__ . '/../parts/Database-connection.php';
include __DIR__ . '/../add-picture.php';

$title = '新增商品';
$pageName = 'products-add';
?>
<?php include __DIR__ . '/../parts/html-head.php'; ?>

<style>
  form .mb-3 .form-text {
    color: red;
  }
</style>

<div class="d-flex w-100 h-100">

  <?php include __DIR__ . '/../parts/html-main.php' ?>

  <?php include __DIR__ . '/products-parts/products-navbar.php' ?>

  <div class="container">
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">新增商品</h5>
            <form name="form1" onsubmit="sendData(event)">
              <div class="mb-3">
                <label for="name" class="form-label">商品名稱</label>
                <input type="text" class="form-control" id="name" name="name">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="main-type" class="form-label">類別</label>
                <select class="form-control" id="type" name="main-type">
                  <!-- <option>選擇類別</option> -->
                  <?php
                  $pmt_sql = "SELECT * FROM product_main_types";
                  $pmt_rows = $pdo->query($pmt_sql)->fetchAll();

                  foreach ($pmt_rows as $r) : ?>
                    <option value="<?= $r['id'] ?>"><?= $r['id'] . " " . $r['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="material" class="form-label">材質</label>
                <input type="text" class="form-control" id="material" name="material">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="style" class="form-label">風格</label>
                <input type="text" class="form-control" id="style" name="style">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="price" class="form-label">價格</label>
                <input type="number" class="form-control" id="price" name="price">
                <div class="form-text"></div>
              </div>
              <!-- Dummy placeholder for now -->
              <!-- 目前圖片暫時不加進資料庫 -->
              <div class="mb-3">
                <label for="image" class="form-label">圖片</label>
                <input class="form-control" type="file" id="image" accept=".jpg, .jpeg, .png">
              </div>
              <button type="submit" class="btn btn-primary">新增</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal彈跳視窗 -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">資料新增結果</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success" role="alert">
            <!-- 綠色背景 -->
            資料新增成功
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
          <a href="products.php" class="btn btn-primary">跳到列表頁</a>
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
            <!-- 紅色背景 -->
            資料失敗
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
          <a href="product-admin.php" class="btn btn-primary">跳到列表頁</a>
        </div>
      </div>
    </div>
  </div>
  </nav>
</div>

<?php include __DIR__ . '/../parts/html-js.php' ?>

<script>
  //解構賦直
  const {
    name: nameField,
    type: typeField,
    material: materialField,
    style: styleField,
    price: priceField,
    image: imageField,
  } = document.form1;

  async function sendData(e) {
    // 欄位的外觀要回復原來的狀態
    //
    //
    nameField.style.border = "1px solid #CCC";
    nameField.nextElementSibling.innerHTML = '';
    typeField.style.border = "1px solid #CCC";
    // typeField.innerHTML = '';
    materialField.style.border = "1px solid #CCC";
    materialField.nextElementSibling.innerHTML = '';
    styleField.style.border = "1px solid #CCC";
    styleField.nextElementSibling.innerHTML = '';
    priceField.style.border = "1px solid #CCC";
    priceField.nextElementSibling.innerHTML = '';
    imageField.style.border = "1px solid #CCC";
    imageField.innerHTML = '';

    e.preventDefault(); // 不要讓有外觀的表單以傳統的方式送出

    let isPass = true; // 有沒有通過檢查, 預設值為 true

    // TODO: 檢查資料的格式

    if (nameField.value.trim() === '') {
      isPass = false;
      nameField.style.border = "2px solid red";
      nameField.nextElementSibling.innerHTML = '請輸入名字';
    }

    if (typeField.value.trim() === '') {
      isPass = false;
      typeField.style.border = "2px solid red";
      // typeField.innerHTML = '請輸入類別';
    }

    if (materialField.value.trim() === '') {
      isPass = false;
      materialField.style.border = "2px solid red";
      materialField.nextElementSibling.innerHTML = '請輸入材質';
    }

    if (styleField.value.trim() === '') {
      isPass = false;
      styleField.style.border = "2px solid red";
      styleField.nextElementSibling.innerHTML = '請輸入風格';
    }

    // price 若有填才檢查格式, 沒填不檢查格式
    if (priceField.value.trim() === '') {
      isPass = false;
      priceField.style.border = "2px solid red";
      priceField.nextElementSibling.innerHTML = '請輸入價格';
    }

    if (imageField.value) {
      if (imageField.files.length <= 1) {
        // 呼叫 Imgur API 
        image = await uploadImage(imageField);
        console.log(image);

        if (!image) {
          isPass = false;
          imageField.style.border = "2px solid red";
          imageField.nextElementSibling.innerHTML = '檔案格式錯誤';
        }
      } else {
        imageField.style.border = "2px solid red";
        imageField.nextElementSibling.innerHTML = '最多一張圖片';
      }
    } else {
      image = "";
    }

    // 如果欄位都有通過檢查, 才要發 AJAX
    if (isPass) {
      const fd = new FormData(document.form1); // 看成沒有外觀的表單
      fd.append('url', image);

      fetch('./products-api/products-add-api.php', {
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
          // alert('資料新增發生錯誤' + ex)
          failureInfo.innerHTML = '資料新增發生錯誤' + ex;
          failureModal.show();
        })
    }

  }

  //使用一行 JavaScript 建立modal
  const successModal = new bootstrap.Modal('#successModal');
  const failureModal = new bootstrap.Modal('#failureModal');
  const failureInfo = document.querySelector('#failureModal .alert-danger');
  // #failureModal .alert-danger記得空格
</script>
<?php include __DIR__ . '/../parts/html-footer.php' ?>