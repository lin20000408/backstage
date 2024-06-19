<?php
// require __DIR__ .'/parts/admin-required.php';
require __DIR__ . '/../parts/Database-connection.php';
include __DIR__ . '/../add-picture.php';
$title = '編輯商品';
$pageName = 'products-edit';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (empty($id)) {
  header('Location: product-admin.php');
  exit;
}

$r = $pdo->query(
  "SELECT p.*,
      COALESCE(pmt.name, '') AS pmt_name,
      COALESCE(pp.url, '') AS pp_url
    FROM products p
    LEFT JOIN product_main_types pmt ON p.pmt_id = pmt.id
    LEFT JOIN product_photos pp ON p.id = pp.product_id
    WHERE p.id = $id"
)->fetch();

if (empty($r)) {
  header('Location: product-admin.php');
  exit;
}
?>

<?php include __DIR__ . '/../parts/html-head.php'; ?>

<style>
  form .mb-3 .form-text {
    color: red;
  }

  container img {
    width: 100%;
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
            <h5 class="card-title">編輯商品</h5>
            <form name="form1" onsubmit="sendData(event)">
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <div class="mb-3">
                <label class="form-label">商品編號</label>
                <input type="text" class="form-control" value="<?= $r['id'] ?>" disabled>
              </div>
              <div class="mb-3">
                <label for="name" class="form-label">商品名稱</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $r['name'] ?>">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="main-type" class="form-label">類別</label>
                <select class="form-control" id="type" name="main-type">
                  <!-- <option>選擇類別</option> -->
                  <?php
                  $pmt_sql = "SELECT * FROM product_main_types";
                  $pmt_rows = $pdo->query($pmt_sql)->fetchAll();

                  foreach ($pmt_rows as $pmt_r) : ?>
                    <option value="<?= $pmt_r['id'] ?>"><?= $pmt_r['id'] . " " . $pmt_r['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="material" class="form-label">材質</label>
                <input type="text" class="form-control" id="material" name="material" value="<?= $r['material_details'] ?>">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="style" class="form-label">風格</label>
                <input type="text" class="form-control" id="style" name="style" value="<?= $r['style'] ?>">
                <div class="form-text"></div>
                <!-- input type=date 每個瀏覽器顯示不一樣 如果要確保每個顯示的一樣 那可以套用jquery ui等框架 -->
              </div>
              <div class="mb-3">
                <label for="price" class="form-label">價格</label>
                <input type="number" class="form-control" id="price" name="price" value="<?= $r['price'] ?>">
                <div class="form-text"></div>
              </div>
              <div class="mb-3">
                <label for="image" class="form-label">圖片</label>
                <img src="<?= $r['pp_url'] ?>" alt="" class="img-fluid mb-1">
                <input class="form-control" type="file" id="image" accept=".jpg, .jpeg, .png">
              </div>
              <button type="submit" class="btn btn-primary">修改</button>
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
          <h1 class="modal-title fs-5">資料修改結果</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success" role="alert">
            <!-- 綠色背景 -->
            資料修改成功
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">重新修改</button>
          <a href="javascript:location.href=document.referrer" class="btn btn-primary">跳到列表頁</a>
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
            <!-- 紅色背景 -->
            資料修改失敗
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">重新修改</button>
          <a href="javascript:location.href=document.referrer" class="btn btn-primary">跳到列表頁</a>
        </div>
        <!-- location.href=document可以直接更新頁面 不會像back只有跳回頁面 沒有更新 -->
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
    nameField.style.border = "1px solid #CCC";
    nameField.nextElementSibling.innerHTML = '';
    typeField.style.border = "1px solid #CCC";
    materialField.style.border = "1px solid #CCC";
    materialField.nextElementSibling.innerHTML = '';
    styleField.style.border = "1px solid #CCC";
    styleField.nextElementSibling.innerHTML = '';
    priceField.style.border = "1px solid #CCC";
    priceField.nextElementSibling.innerHTML = '';
    imageField.innerHTML = '';

    e.preventDefault(); // 不要讓有外觀的表單以傳統的方式送出

    let isPass = true; // 有沒有通過檢查, 預設值為 true

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
      const fd = new FormData(document.form1);
      // 看成沒有外觀的表單
      fd.append('url', image);

      fetch('./products-api/products-edit-api.php', {
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
              failureInfo.innerHTML = '資料修改沒有成功';
            }
            failureModal.show();
          }
        })
        .catch(ex => {
          console.log(ex);
          // alert('資料新增發生錯誤' + ex)
          failureInfo.innerHTML = '資料修改發生錯誤' + ex;
          failureModal.show();
        })
    }
  }

  const successModal = new bootstrap.Modal('#successModal');
  const failureModal = new bootstrap.Modal('#failureModal');
  const failureInfo = document.querySelector('#failureModal .alert-danger');
</script>

<?php include __DIR__ . '/../parts/html-footer.php' ?>