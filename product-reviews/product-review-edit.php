<?php
// require __DIR__ .'/parts/admin-required.php';
require __DIR__ . '/../parts/Database-connection.php';
$title = '編輯商品評價';
$pageName = 'product-review-edit';


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
        if (empty($id)) {
            header('Location: product-review-all-id.php');
            exit;
        }

        $r = $pdo->query("SELECT order_details.*,
        product_reviews.*
        
        FROM `midterm`.product_reviews
        INNER JOIN `midterm`.order_details 
        ON order_details.product_reviews_id = product_reviews.id WHERE product_reviews_id=$id")->fetch();
        if (empty($r)) {
            header('Location: product-review-all-id.php');
            exit;
        }
        ?>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">編輯商品評價</h5>
                        <form name="form1" onsubmit="sendData(event)">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">商品名稱</label>
                                <input type="text" id="name" name="name" class="form-control" value="<?= $r['product_name'] ?>" disabled>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="size" class="form-label">商品尺寸</label>
                                <input type="text" id="size" name="size" class="form-control" value="<?= $r['product_size'] ?>" disabled>
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                                <label for="color" class="form-label">商品顏色</label>
                                <input type="text" id="color" name="color" class="form-control" value="<?= $r['product_color'] ?>" disabled>
                                <div class="form-text"></div>
                            </div>
                            <!-- <div class="mb-3">
                                <label for="stars" class="form-label">星等</label>
                                <select  id="stars" aria-label="Example select with button addon" name="stars">
                                    <option selected>請選擇</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5 </option>
                                </select>
                                <div class="form-text"></div>
                                
                            </div> -->
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
                            </h1>
                            <div class="mb-3">
                                <label for="content" class="form-label">評價內容</label>
                                <input type="text" class="form-control" id="content" name="content" value="<?= $r['content'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-3">
                               
                                
                                <label for="photo" class="form-label">照片</label>

                                <img style="display:block;height:180px;" src="<?= $r['photo'] ?>" alt="" class="img-fluid mb-1">
                                <input class="form-control" type="file" name="photo" id="photo" multiple>
                                <div class="form-text"></div>
                                <!-- input type=date 每個瀏覽器顯示不一樣 如果要確保每個顯示的一樣 那可以套用jquery ui等框架 -->
                            </div>
                            <div class="mb-3">
                                <label for="reviewTime" class="form-label">評價時間</label>
                                <input type="date" class="form-control" id="reviewTime" name="reviewTime" value="<?= $r['review_time'] ?>" disabled>
                                <div class="form-text"></div>
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
                 
                    <a href="javascript: location.href=document.referrer" class="btn btn-primary">修改完成</a>
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
             
                    <a href="javascript: location.href=document.referrer" class="btn btn-primary">跳到列表頁</a>
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
    const {
        product_name: product_nameField,
        product_size: product_sizeField,
        product_color: product_colorField,
        stars: starsField,
        content: contentField,
        photo: photoField

    } = document.form1;





    async   function sendData(e) {
        // 欄位的外觀要回復原來的狀態

        starsField.style.border = "1px solid #CCC";
        starsField.nextElementSibling.innerHTML = '';
        contentField.style.border = "1px solid #CCC";
        contentField.nextElementSibling.innerHTML = '';
        photoField.style.border = "1px solid #CCC";
        photoField.nextElementSibling.innerHTML = '';



        e.preventDefault(); // 不要讓有外觀的表單以傳統的方式送出

        let isPass = true; // 有沒有通過檢查, 預設值為 true




        // if (contentField.value.trim() === '') {
        //     isPass = false;
        //     contentField.style.border = "2px solid red";
        //     contentField.nextElementSibling.innerHTML = '請輸入content';
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
            const fd = new FormData(document.form1);
            // 看成沒有外觀的表單
            fd.append("url", Imgur);
            fetch('product-reviews-api/product-review-edit-api.php', {
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