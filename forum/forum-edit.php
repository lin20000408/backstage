<?php require '.././parts/Database-connection.php' ?>
<?php
// 設定pageName(頁面名稱)
$pageName = 'forum-edit';

// 管理者照片
include '../add-picture.php';

// 檢查有沒有帶參數進來，有的話轉換成整數(intval)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 如果沒有 或是變成整數、0
if (empty($id)) {
  //   跳到列表頁，之後離開
  header('Location: forum.php');
  exit;
}
//發文者文章資訊
$row = $pdo->query("SELECT * FROM articles WHERE id=$id")->fetch();
//文章類別
$Type = "SELECT `articles`.`type` FROM `articles` GROUP BY `articles`.`type`";
$selectType = $pdo->query($Type)->fetchAll(PDO::FETCH_NUM);
?>


<?php include '.././parts/html-head.php' ?>

<div class="d-flex w-100 h-100">

  <?php include '.././parts/html-main.php' ?>

  <?php include './forum-parts/forum-navbar.php' ?>

  <div class="container">
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

              <!-- 讓表單送出編號值 (這是隱藏欄位) -->
              <input type="hidden" name="id" value="<?= $row['id'] ?>">

              <!-- 發文者名稱 -->
              <div class="mb-3">
                <label for="user_name" class="form-label">發文者名稱</label>
                <input type="text" class="form-control" id="user_name" value="<?= $row['user_name'] ?>" disabled>
                <div class="form-text"></div>
              </div>

              <!-- 文章類別 -->
              <select class="form-select form-select-sm mb-3" aria-label=".form-select-sm example" name="type">
                <?php foreach ($selectType as $t) : ?>
                  <option value="<?= $t[0] ?>" <?= $t[0] == $row['type'] ? 'selected ' : '' ?>>
                    <?= $t[0] ?>
                  </option>
                <?php endforeach ?>
              </select>
              <div class="form-text"></div>

              <!-- 文章標題欄位 -->
              <div class="mb-3">
                <label for="head" class="form-label">文章標題</label>
                <input type="text" class="form-control" id="head" name="head" value="<?= $row['head'] ?>">
                <div class="form-text"></div>
              </div>

              <!-- 文章內容欄位 -->
              <div class="mb-3">
                <label for="content" class="form-label">文章內容</label>
                <textarea id="content" name="content" cols="30" rows="5" class="form-control"><?= $row['content'] ?></textarea>
                <div class="form-text"></div>
              </div>

              <!-- 文章圖片上傳 -->
              <div class="mb-3">
                <label for="image" class="form-label">圖片修改</label>
                <input class="form-control" type="file" id="image" name="image" multiple>
                <div class="form-text"></div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"onclick="location.href = 'forum.php'">返回列表</button>
                <button type="submit" class="btn btn-primary">修改</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
  </nav>
</div>

<!-- 貼文修改成功視窗 -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">貼文修改結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          貼文修改成功
        </div>
      </div>

      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button> -->
        <a href="forum.php" class="btn btn-primary">回到總表頁</a>
      </div>

    </div>
  </div>
</div>

<!-- 貼文修改失敗視窗 -->
<div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">貼文修改結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          貼文修改失敗
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">修改</button> -->
        <a href="forum.php" class="btn btn-primary">回到總表頁</a>
      </div>
    </div>
  </div>
</div>

<?php include '.././parts/html-js.php' ?>

<script>
  var myModal;
  // 在頁面加載(DOMContentLoaded)完成後自動彈出模態視窗
  document.addEventListener('DOMContentLoaded', function() {
    myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
    myModal.show();
  });
</script>

<script>
  const {
    type: typeEl,
    head: headEl,
    content: contentEl,
    image: formFileEl
  } = document.form1;


  async function sendData(e) {

    // 欄位外觀回復原來狀態
    headEl.style.border = "1px solid #ccc"
    headEl.nextElementSibling.innerHTML = '';

    contentEl.style.border = "1px solid #ccc"
    contentEl.nextElementSibling.innerHTML = '';

    e.preventDefault();

    let isPass = true; //有沒有通過檢查，預設值為true

    // TODO:檢查資料格式

    // if (typeEl.value == "<?= $row['type'] ?>") {
    //   typeEl.value = "<?= $row['type'] ?>";
    //   console.log("1");
    // }
    // 標題是否有填寫
    if (headEl.value.length < 3) {
      isPass = false;
      headEl.style.border = "2px solid red"
      headEl.nextElementSibling.innerHTML = '請輸入至少三個字';
    }

    // 內容是否有填寫
    if (contentEl.value.length < 5) {
      isPass = false;
      contentEl.style.border = "2px solid red"
      contentEl.nextElementSibling.innerHTML = '請輸入至少五個字';
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
      Imgur = "<?= $row['picture'] ?>";
    }

    if (isPass) {
      const fd = new FormData(document.form1);
      fd.append("url", Imgur);
      myModal.hide();
      //傳送
      fetch('./forum-api./forum-edit-api.php', {
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