<?php
// require __DIR__ . '/parts/admin-required.php';
require __DIR__ . '/../parts/Database-connection.php';
$title = '新增商品評價';
$pageName = 'product-review-add';

include __DIR__ . '/../add-picture.php';
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>

<style>
    form .mb-3 .form-text {
        color: red;
    }

    nav.navbar .nav-item .nav-link.active {
        border-radius: 10px;
        background-color: #0d6efd;
        color: white;
        font-weight: 800;
    }
</style>

<div class="d-flex w-100 h-100">

    <?php include __DIR__ . '/product-reviews-parts/product-review-main.php' ?>


    <?php include __DIR__ . '/product-reviews-parts/product-review-navbar.php' ?>

    <div class="container">
        <?php
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $r = $pdo->query("SELECT * FROM `midterm`.order_details
    WHERE id=$id ")->fetch();


        ?>


        <div class="row">
            <div class="col-6">
                <div class="card">

                    <div class="card-body">
                        <h5 class="card-title">新增評價</h5>


                        <form name="form1" onsubmit="sendData(event)">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <div class="mb-3">
                                <label for="order_id" class="form-label">訂單編號</label>
                                <input type="text" class="form-control" id="order_id" name="order_id" value="<?= $r['order_id'] ?>" disabled>
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="productName" class="form-label">商品名稱</label>
                                <input type="text" class="form-control" id="productName" name="productName" value="<?= $r['product_name'] ?>" disabled>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="productSize" class="form-label">商品尺寸</label>
                                <input type="text" class="form-control" id="productSize" name="productSize" value="<?= $r['product_size'] ?>" disabled>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="productColor" class="form-label">商品顏色</label>
                                <input type="text" class="form-control" id="productColor" name="productColor" value="<?= $r['product_color'] ?>" disabled>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">評價照片</label>
                                <input class="form-control" type="file" id="photo" name="photo" multiple>
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">評價內容</label>
                                <textarea class="form-control" name="content" id="content" cols="30" rows="3"></textarea>
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="stars" class="form-label">星等</label>
                                <input type="hidden" id="stars" name="stars">
                                <div class="rating">
                                    <span id="star1" class="fa-regular fa-star fa-2x" onclick="showrating(1)"></span>
                                    <span id="star2" class="fa-regular fa-star fa-2x" onclick="showrating(2)"></span>
                                    <span id="star3" class="fa-regular fa-star fa-2x" onclick="showrating(3)"></span>
                                    <span id="star4" class="fa-regular fa-star fa-2x" onclick="showrating(4)"></span>
                                    <span id="star5" class="fa-regular fa-star fa-2x" onclick="showrating(5)"></span>
                                </div>
                                <div class="form-text"></div>
                            </div>


                            <button type="submit" class="btn btn-primary">新增</button>
                        </form>

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

                    <a href="product-review-all-id.php" class="btn btn-primary">新增完成</a>
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
                    <button type="button" scr="product-review-add.php?id=$id" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
                    <a href="product-review-all-id.php" class="btn btn-primary">跳到列表頁</a>
                </div>
            </div>
        </div>
    </div>
    </nav>
</div>
<?php include __DIR__ . '/../parts/html-js.php' ?>

<script>
    //星星
    let rating_last = 0;

    function showrating(n) {
        for (let i = 1; i <= 5; i++) {
            let star = document.getElementById("star" + i);
            if (i <= n) {
                star.classList.remove("fa-regular");
                star.classList.add("fa-solid");
                star.style.color = "#DFB63C";
            } else {
                star.classList.remove("fa-solid");
                star.classList.add("fa-regular");
                star.style.color = "#2E3436";
            }
        }
        rating_last = n;
        document.getElementById('stars').value = n;
    }

    // //傳入訂單詳情



    // 傳入訂單詳情
    // 建構函式
    const {
        // order_id: order_idField,
        // product_id: product_idField,
        // productName: productNameField,
        // productSize: productSizeField,
        // productColor: productColorField,
        photo: photoField,
        content: contentField,
        stars: starsField
    } = document.form1;




    async function sendData(e) {
        // 欄位的外觀要回復原來的狀態
        // order_idField.style.border = "1px solid #CCC";
        // order_idField.nextElementSibling.innerHTML = '';
        // product_id.style.border = "1px solid #CCC";
        // product_id.nextElementSibling.innerHTML = '';
        // productNameField.style.border = "1px solid #CCC";
        // productName.nextElementSibling.innerHTML = '';
        // productSizeField.style.border = "1px solid #CCC";
        // productSizeField.nextElementSibling.innerHTML = '';
        // productColorField.style.border = "1px solid #CCC";
        // productColorField.nextElementSibling.innerHTML = '';
        photoField.style.border = "1px solid #CCC";
        photoField.nextElementSibling.innerHTML = '';
        contentField.style.border = "1px solid #CCC";
        contentField.nextElementSibling.innerHTML = '';
        starsField.style.border = "1px solid #CCC";
        starsField.nextElementSibling.innerHTML = '';


        e.preventDefault(); // 不要讓有外觀的表單以傳統的方式送出

        let isPass = true; // 有沒有通過檢查, 預設值為 true

        // TODO: 檢查資料的格式

        // 姓名是必填, 長度要 2 以上
        // if (starsField.value.trim() === '') {
        //     isPass = false;
        //     starsField.style.border = "2px solid red";
        //     starsField.nextElementSibling.innerHTML = '請輸入名字';
        // }

     

        if (photoField.value) {
            if (photoField.files.length <= 1) {
                try {
                    // 呼叫Imgur api
                    Imgur = await uploadImage(photoField);
                    console.log(Imgur);

                    if (!Imgur) {
                        isPass = false;
                        photoField.style.border = "2px solid red";
                        photoField.nextElementSibling.innerHTML = '檔案格式錯誤';
                    }
                } catch (ex) {
                    console.error(ex);
                }
            } else {
                photoField.style.border = "2px solid red";
                photoField.nextElementSibling.innerHTML = '最多一張圖片';
            }
        } else {
            Imgur = "";
        }



        // 如果欄位都有通過檢查, 才要發 AJAX
        if (isPass) {
            const fd = new FormData(document.form1); // 看成沒有外觀的表單
            fd.append("url", Imgur);
            fetch('product-reviews-api/product-review-add-api.php', {
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