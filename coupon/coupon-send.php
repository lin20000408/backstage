<?php
require __DIR__ . '/../parts/Database-connection.php';

$title = '發送優惠劵';

// 設定pageName(頁面名稱)
$pageName = 'coupon-send-management';

//管理者資訊
include __DIR__ . '/../add-picture.php';

// 查询数据库以获取所有优惠券名称
$query = "SELECT name,money,starting_time,end_time FROM coupon";
$stmt = $pdo->query($query);
$couponData = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 查询数据库以获取會員編號
$query2  = "SELECT id,name FROM user";
$stmt2 = $pdo->query($query2);
$userData = $stmt2->fetchAll(PDO::FETCH_COLUMN);
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>
<style>
    /* nextElementSibling的style */
    form .mb-3 .form-text {
        color: red;
    }
</style>

<div class="d-flex w-100 h-100">
    <?php include __DIR__ . '/../parts/html-main.php' ?>
    <?php include __DIR__ . '/coupon-parts/coupon-navbar.php' ?>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-6">

                <!-- 卡片外觀 -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">發送優惠劵</h5>

                        <!-- 表單 -->
                        <form name="form1" onsubmit="sendData(event)" enctype="multipart/form-data">

                            <!-- 優惠劵名稱欄位 -->
                            <div class="mb-3">
                                <label for="coupon_name" class="form-label">優惠劵名稱</label>
                                <select id="coupon_name" name="coupon_name" onchange="updateCouponInfo()">
                                    <option value="">-- 請選擇 --</option> <!-- 这是空值的默认选项 -->
                                    <?php foreach ($couponData as $couponName) : ?>
                                        <option value="<?php echo $couponName; ?>"><?php echo $couponName; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text"></div>
                            </div>

                            <!-- 會員編號欄位 -->
                            <div class="mb-3">
                                <label for="user_id" class="form-label">會員編號</label>
                                <select id="user_id" name="user_id" onchange="updateCouponInfo()">
                                    <option value="">-- 請選擇 --</option> <!-- 这是空值的默认选项 -->
                                    <?php foreach ($userData as $userId) : ?>
                                        <option value="<?php echo $userId; ?>"><?php echo $userId; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text"></div>
                            </div>

                            <!-- 折抵金額欄位 -->
                            <div class="mb-3">
                                <label for="coupon_money" class="form-label">折抵金額</label>
                                <input type="text" class="form-control" id="coupon_money" name="coupon_money" value="<?= isset($row['coupon_money']) ? $row['coupon_money'] : '' ?>" disabled>
                            </div>

                            <!-- 開始日期欄位 -->
                            <div class="mb-3">
                                <label for="starting_time" class="form-label">開始日期</label>
                                <input type="date" class="form-control" id="starting_time" name="starting_time" value="<?= isset($row['starting_time']) ? $row['starting_time'] : '' ?>" disabled>
                            </div>

                            <!-- 結束日期欄位 -->
                            <div class="mb-3">
                                <label for="end_time" class="form-label">結束日期</label>
                                <input type="date" class="form-control" id="end_time" name="end_time" value="<?= isset($row['end_time']) ? $row['end_time'] : '' ?>" disabled>
                            </div>

                            <!-- 會員姓名欄位 -->
                            <div class="mb-3">
                                <label for="user_name" class="form-label">會員姓名</label>
                                <input type="text" class="form-control" id="user_name" name="user_name" value="<?= isset($row['user_name']) ? $row['user_name'] : '' ?>" disabled>
                            </div>

                            <!-- 送出按鈕 -->
                            <button type="submit" class="btn btn-primary">確定發送</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 優惠劵發送成功視窗 -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">優惠劵發送結果</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" role="alert">
                        優惠劵發送成功
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續發送</button>
                    <a href="coupon-send-management.php" class="btn btn-primary">回到發送管理</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 優惠劵發送失敗視窗 -->
    <div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">優惠劵發送結果</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        優惠劵發送失敗
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">修改</button>
                    <a href="coupon-send-management.php" class="btn btn-primary">回到發送管理</a>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../parts/html-js.php' ?>
    <script>
        function updateCouponInfo() {
            // 获取选定的优惠券名称和用户ID
            let couponName = document.getElementById('coupon_name').value;
            let userId = document.getElementById('user_id').value;

            // 在控制台打印优惠券名称和用户ID，以便调试
            console.log("Coupon Name: ", couponName);
            console.log("User ID: ", userId);

            // 创建 FormData 对象，将优惠券名称和用户ID添加到其中
            const fd = new FormData();
            fd.append("coupon_name", couponName);
            fd.append("user_id", userId);

            // 发送 POST 请求到 coupon-user-api.php
            fetch('./coupon-api/coupon-user-api.php', {
                    method: 'POST',
                    body: fd
                })
                .then(response => response.json())
                .then(data => {
                    // 处理从服务器返回的数据
                    console.log("Response: ", data);
                    if (data.success) {
                        // 如果请求成功，更新表单中的优惠券信息
                        let couponMoney = document.querySelector('#coupon_money')
                        let startingTime = document.querySelector('#starting_time')
                        let endTime = document.querySelector('#end_time')
                        let userName = document.querySelector('#user_name')

                        // 清空之前的值
                        couponMoney.value = ''
                        startingTime.value = ''
                        endTime.value = ''
                        userName.value = ''

                        // 设置新的优惠券信息
                        couponMoney.value = data.data.coupon_money;
                        startingTime.value = data.data.starting_time;
                        endTime.value = data.data.end_time;
                        userName.value = data.data.user_name; // 正确设置用户姓名
                        console.log("Coupon data: ", data.data);
                    } else {
                        // 如果请求失败，打印错误信息到控制台
                        console.error("Error: ", data.error);
                    }
                })
                .catch(error => {
                    // 如果发生错误，打印错误信息到控制台
                    console.error("Fetch Error: ", error);
                });
        }

        // document.form1拿到的coupon_name、user_id的值給coupon_nameField、user_idField變數
        const {
            coupon_name: coupon_nameField,
            user_id: user_idField,
        } = document.form1;

        function sendData(e) {

            // 欄位外觀要回復原來狀態
            coupon_nameField.style.border = "1px solid #ccc"
            coupon_nameField.nextElementSibling.innerHTML = '';
            user_idField.style.border = "1px solid #ccc"
            user_idField.nextElementSibling.innerHTML = '';

            e.preventDefault(); //不要讓有外觀的表單以傳統方式送出

            // 預設全部都通過，所以只要一個欄位檢查沒過就是false
            let isPass = true; //有沒有通過檢查，預設值為true

            // TODO:檢查資料格式

            // 優惠劵名稱是必填
            if (!coupon_nameField.value) {
                isPass = false;
                coupon_nameField.style.border = "2px solid red"
                coupon_nameField.nextElementSibling.innerHTML = '請輸入優惠劵名稱';
            }

            // 會員編號是必填
            if (!user_idField.value) {
                isPass = false;
                user_idField.style.border = "2px solid red"
                user_idField.nextElementSibling.innerHTML = '請輸入會員編號';
            }

            // 全部都有通過檢查,才要發ajax
            if (isPass) {

                //有外觀的表單資料(document.form1)(讓使用者輸入)複製到沒有外觀的表單(傳送給後端,通過AJAX傳送)
                const fd = new FormData(document.form1);

                // 打api
                fetch('./coupon-api/coupon-send-api.php', {
                        method: 'POST',
                        body: fd
                    })
                    .then(r => r.json())
                    .then(result => {
                        console.log(result);

                        // 用alert是不好的，會有同步的問題
                        if (result.success) {
                            // alert('優惠劵發送成功')

                            // 視窗顯示出來
                            successModal.show();
                        } else {
                            // alert('優惠劵發送失敗')

                            // 如果有錯誤訊息
                            if (result.error) {
                                failureInfo.innerHTML = result.error;
                            } else {
                                failureInfo.innerHTML = '優惠劵發送失敗';
                            }
                            failureModal.show();
                        }
                    })

                    // 除錯
                    .catch(ex => {
                        console.log(ex);
                        // alert('優惠劵發送發生錯誤' + ex)
                        failureInfo.innerHTML = '優惠劵發送發生錯誤' + ex;
                        failureModal.show();
                    })
            }
        }

        // 透過js控制資料發送成功/失敗視窗
        const successModal = new bootstrap.Modal('#successModal')
        const failureModal = new bootstrap.Modal('#failureModal')

        // 抓錯誤訊息
        const failureInfo = document.querySelector('#failureModal .alert-danger')
    </script>
    <?php include __DIR__ . '/../parts/html-footer.php' ?>