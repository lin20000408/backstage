<?php require '.././parts/Database-connection.php' ?>
<?php
// 設定pageName(頁面名稱)
$pageName = 'forum-add';

// 管理者照片
include '../add-picture.php';

$Article = "SELECT user.id, user.name FROM user";
$articleUser = $pdo->query($Article)->fetchAll(PDO::FETCH_NUM);

$Type = "SELECT `product_main_types`.`name` FROM `product_main_types` ";
$selectType = $pdo->query($Type)->fetchAll(PDO::FETCH_NUM);
?>

<?php include '.././parts/html-head.php' ?>

<div class="d-flex w-100 h-100">

  <?php include '.././parts/html-main.php' ?>

  <?php include './forum-parts/forum-navbar.php' ?>

  <div class="container">

    <!-- Button trigger modal
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop"></button> -->

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- 觸發按鈕 -->
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <!-- add表單 送表單記得要設name-->
            <form name="form1" action="" onsubmit="sendData(event)">
              <!-- 發文者名稱 -->
              <select class="form-select form-select-sm mb-3" aria-label=".form-select-sm example" name="selectedUser">
                <option selected disabled>選擇發文者</option>
                <?php foreach ($articleUser as $r) : ?>
                  <option value="<?= $r[0] . ':' . $r[1] ?>"><?= $r[1] ?></option>
                <?php endforeach ?>
              </select>
              <div class="form-text"></div>

              <!-- 文章類別 -->
              <select class="form-select form-select-sm mb-3" aria-label=".form-select-sm example" name="type">
                <option selected disabled>選擇文章類型</option>
                <?php foreach ($selectType as $t) : ?>
                  <option value="<?= $t[0] ?>"><?= $t[0] ?></option>
                <?php endforeach ?>
              </select>
              <div class="form-text"></div>

              <!-- 文章標題欄位 -->
              <div class="mb-3">
                <label for="articleHead" class="form-label">文章標題</label>
                <input type="text" class="form-control" id="articleHead" name="articleHead">
                <div class="form-text">請輸入至少三個字</div>
              </div>
              <!-- 文章內容欄位 -->
              <div class="mb-3">
                <label for="articleContent" class="form-label">文章內容</label>
                <textarea id="articleContent" name="articleContent" cols="30" rows="5" class="form-control"></textarea>
                <div class="form-text">請輸入至少五個字</div>
              </div>
              <!-- 文章圖片上傳 -->
              <div class="mb-3">
                <label for="image" class="form-label">圖片上傳</label>
                <input class="form-control" type="file" id="image" name="image" multiple>
                <div class="form-text"></div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="location.href = 'forum.php'">返回列表</button>
                <button type="submit" class="btn btn-primary" name="submit">新增</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
  </nav>

  <!-- 貼文新增成功視窗 -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">貼文新增結果</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success" role="alert">
            貼文新增成功
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="location.href = 'forum-add.php'">繼續新增</button>
          <a href="forum.php" class="btn btn-primary">回到總表頁</a>
        </div>
      </div>
    </div>
  </div>

  <!-- 貼文新增失敗視窗 -->
  <div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">貼文新增結果</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger" role="alert">
            貼文新增失敗
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">修改</button>
          <a href="coupon.php" class="btn btn-primary">回到總表頁</a>
        </div>
      </div>
    </div>
  </div>

</div>

<?php include '.././parts/html-js.php' ?>

<script>
  var modalInstance;
  // 在頁面加載(DOMContentLoaded)完成後自動彈出模態視窗
  document.addEventListener('DOMContentLoaded', function() {
    modalInstance = new bootstrap.Modal(document.getElementById('staticBackdrop'));
    modalInstance.show();
  });

  const {
    selectedUser: selectedUserEl,
    type: typeEl,
    articleHead: articleHeadEl,
    articleContent: articleContentEl,
    image: formFileEl
  } = document.form1;

  async function sendData(e) {
    // 欄位外觀回復原來狀態
    selectedUserEl.style.border = "1px solid #ccc"
    selectedUserEl.nextElementSibling.innerHTML = '';

    typeEl.style.border = "1px solid #ccc"
    typeEl.nextElementSibling.innerHTML = '';

    articleHeadEl.style.border = "1px solid #ccc"
    articleHeadEl.nextElementSibling.innerHTML = '';

    articleContentEl.style.border = "1px solid #ccc"
    articleContentEl.nextElementSibling.innerHTML = '';

    formFileEl.style.border = "1px solid #ccc";
    formFileEl.nextElementSibling.innerHTML = '';

    e.preventDefault(); //不用預設方式送出


    let isPass = true; //有沒有通過檢查，預設值為true

    // TODO:檢查資料格式
    // 發文者是否有填寫
    if (selectedUserEl.value == '選擇發文者') {
      isPass = false;
      selectedUserEl.style.border = "2px solid red"
      selectedUserEl.firstElementChild.innerHTML = '選擇發文者';
    }

    if (typeEl.value == '選擇文章類型') {
      isPass = false;
      typeEl.style.border = "2px solid red"
      typeEl.firstElementChild.innerHTML = '選擇文章類型';
    }

    // 標題是否有填寫
    if (articleHeadEl.value.length < 3) {
      isPass = false;
      articleHeadEl.style.border = "2px solid red"
      articleHeadEl.nextElementSibling.innerHTML = '請輸入至少三個字';
    }

    // 內容是否有填寫
    if (articleContentEl.value.length < 5) {
      isPass = false;
      articleContentEl.style.border = "2px solid red";
      articleContentEl.nextElementSibling.innerHTML = '請輸入至少五個字';
    }
    //圖片是否有選擇
    if (formFileEl.value) {
      if (formFileEl.files.length <= 1) {
        //呼叫Imgur api
        Imgur = await uploadImage(formFileEl);
        console.log(Imgur);

        if (!Imgur) {
          isPass = false;
          formFileEl.style.border = "2px solid red";
          formFileEl.nextElementSibling.innerHTML = '檔案格式錯誤';
        }
      } else {
        formFileEl.style.border = "2px solid red";
        formFileEl.nextElementSibling.innerHTML = '最多一張圖片';
      }
    } else {
      Imgur = "";
    }

    //通過才要發ajax
    if (isPass) {
      // 打api FormData()型態 表單 預設enctype="multipart/form-data"
      const fd = new FormData(document.form1);
      fd.append("url", Imgur);
      // 關閉模態視窗
      modalInstance.hide();
      //傳送
      fetch('./forum-api./forum-add-api.php', {
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
              failureInfo.innerHTML = '貼文新增失敗';
            }
            failureModal.show();
          }
        })
        .catch(ex => {
          console.log(ex);
          failureInfo.innerHTML = '貼文新增發生錯誤' + ex;
          failureModal.show();
        })
    }
  }
  // 透過js控制資料新增成功/失敗視窗
  const successModal = new bootstrap.Modal('#successModal');
  const failureModal = new bootstrap.Modal('#failureModal');

  // 抓錯誤訊息
  const failureInfo = document.querySelector('#failureModal .alert-danger');
</script>

<?php include '.././parts/html-footer.php' ?>