<?php
require __DIR__ . '/../parts/Database-connection.php';

$title = '編輯優惠劵';

// 設定pageName(頁面名稱)
$pageName = 'coupon-edit';

//管理者資訊
include __DIR__ . '/../add-picture.php';

// 檢查有沒有帶參數進來，有的話轉換成整數(intval)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 如果沒有 或是變成整數、0
if (empty($id)) {

    //   跳到列表頁，之後離開
    header('Location: coupon.php');
    exit;
}

$row = $pdo->query("SELECT * FROM coupon WHERE id=$id")->fetch();

// 如果沒有資料
if (empty($row)) {

    //   跳到列表頁，之後離開
    header('Location: coupon.php');
    exit;
}
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>

<style>
    /* nextElementSibling的style */
    form .mb-3 .form-text {
        color: red;
    }
</style>

<div class="d-flex w-100 h-100">
    <?php include __DIR__ . '/coupon-edit-main.php' ?>
    <nav class="w-75" style="margin-left: 454px;">
        <?php include __DIR__ . '/coupon-parts/coupon-edit-navbar.php' ?>
        <div class="container" style="overflow: hidden;">
            <div class="row d-flex justify-content-center">
                <div class="col-6">

                    <!-- 卡片外觀 -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">編輯優惠券</h5>

                            <!-- 表單 -->
                            <form name="form1" onsubmit="sendData(event)">

                                <!-- 讓表單送出編號的值 (這是隱藏欄位) -->
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                                <!-- 編號欄位 -->
                                <div class="mb-3">
                                    <label for="id" class="form-label">編號</label>
                                    <input type="text" class="form-control" value="<?= $row['id'] ?>" disabled>
                                </div>

                                <!-- 優惠劵名稱欄位 -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">優惠劵名稱</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= $row['name'] ?>">
                                    <div class="form-text"></div>
                                </div>

                                <!-- 折抵金額欄位 -->
                                <div class="mb-3">
                                    <label for="money" class="form-label">折抵金額</label>
                                    <input type="number" class="form-control" id="money" name="money" min="10" step="10" value="<?= $row['money'] ?>">
                                    <div class="form-text"></div>
                                </div>

                                <!-- 開始日期欄位 -->
                                <div class="mb-3">
                                    <label for="starting_time" class="form-label">開始日期</label>
                                    <input type="date" class="form-control" id="starting_time" name="starting_time" value="<?= $row['starting_time'] ?>">
                                    <div class="form-text"></div>
                                </div>

                                <!-- 結束日期欄位 -->
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">結束日期</label>
                                    <input type="date" class="form-control" id="end_time" name="end_time" value="<?= $row['end_time'] ?>">
                                    <div class="form-text"></div>
                                </div>

                                <!-- 送出按鈕 -->
                                <button type="submit" class="btn btn-primary">確定修改</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>

        <!-- 優惠劵新增成功視窗 -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">優惠劵修改結果</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" role="alert">
                            優惠劵修改成功
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button>
                        <a href="javascript: location.href=document.referrer" class="btn btn-primary">回到總表頁</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 優惠劵新增失敗視窗 -->
        <div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">優惠劵修改結果</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" role="alert">
                            優惠劵修改失敗
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續修改</button>
                        <a href="javascript: location.href=document.referrer" class="btn btn-primary">回到總表頁</a>
                    </div>
                </div>
            </div>
        </div>

        <?php include __DIR__ . '/../parts/html-js.php' ?>
        <script>
            // document.form1拿到的name、money、starting_time、end_time的值給nameField、moneyField、starting_timeField、end_timeField變數
            const {
                name: nameField,
                money: moneyField,
                starting_time: starting_timeField,
                end_time: end_timeField,
            } = document.form1;

            // 設定結束日期不能大於開始日期
            document.getElementById("end_time").addEventListener("input", function() {
                var startDate = new Date(document.getElementById("starting_time").value);
                var endDate = new Date(this.value);

                if (endDate < startDate) {
                    alert("结束日期不能早於開始日期");
                    this.value = ""; // 清除结束日期输入
                }
            });


            function sendData(e) {

                // 欄位外觀要回復原來狀態
                nameField.style.border = "1px solid #ccc"
                nameField.nextElementSibling.innerHTML = '';
                moneyField.style.border = "1px solid #ccc"
                moneyField.nextElementSibling.innerHTML = '';
                starting_timeField.style.border = "1px solid #ccc"
                starting_timeField.nextElementSibling.innerHTML = '';
                end_timeField.style.border = "1px solid #ccc"
                end_timeField.nextElementSibling.innerHTML = '';

                e.preventDefault(); //不要讓有外觀的表單以傳統方式送出

                // 預設全部都通過，所以只要一個欄位檢查沒過就是false
                let isPass = true; //有沒有通過檢查，預設值為true

                // TODO:檢查資料格式

                // 優惠劵名稱是必填，長度要1以上
                if (!nameField.value) {
                    isPass = false;
                    nameField.style.border = "2px solid red"
                    nameField.nextElementSibling.innerHTML = '請輸入優惠劵名稱';
                }

                // 折抵金額是必填
                if (!moneyField.value) {
                    isPass = false;
                    moneyField.style.border = "2px solid red"
                    moneyField.nextElementSibling.innerHTML = '請輸入折抵金額';
                }

                // 開始日期是必填，長度要1以上
                if (!starting_timeField.value) {
                    isPass = false;
                    starting_timeField.style.border = "2px solid red"
                    starting_timeField.nextElementSibling.innerHTML = '請輸入開始日期';
                }

                // 結束日期是必填，長度要1以上
                if (!end_timeField.value) {
                    isPass = false;
                    end_timeField.style.border = "2px solid red"
                    end_timeField.nextElementSibling.innerHTML = '請輸入結束日期';
                }

                // 全部都有通過檢查,才要發ajax
                if (isPass) {

                    //有外觀的表單資料(document.form1)(讓使用者輸入)複製到沒有外觀的表單(傳送給後端,通過AJAX傳送)
                    const fd = new FormData(document.form1);

                    // 打api
                    fetch('./coupon-api/coupon-edit-api.php', {
                            method: 'POST',
                            body: fd
                        })
                        .then(r => r.json())
                        .then(result => {
                            console.log(result);

                            if (result.success) {

                                // 視窗顯示出來
                                successModal.show();
                            } else {

                                // 如果有錯誤訊息
                                if (result.error) {
                                    failureInfo.innerHTML = result.error;
                                } else {
                                    failureInfo.innerHTML = '優惠劵修改沒有成功';
                                }
                                failureModal.show();
                            }
                        })

                        // 除錯
                        .catch(ex => {
                            console.log(ex);
                            failureInfo.innerHTML = '優惠劵修改發生錯誤' + ex;
                            failureModal.show();
                        })
                }
            }

            // 透過js控制資料修改成功/失敗視窗
            const successModal = new bootstrap.Modal('#successModal')
            const failureModal = new bootstrap.Modal('#failureModal')

            // 抓錯誤訊息
            const failureInfo = document.querySelector('#failureModal .alert-danger')
        </script>
        <?php include __DIR__ . '/../parts/html-footer.php' ?>