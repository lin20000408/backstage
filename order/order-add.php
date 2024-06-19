    <?php require '.././parts/Database-connection.php';
    $title = '新增訂單';
    $pageName = 'order-';
    $navbarName = 'orderAdd';
    include __DIR__ . '/../add-picture.php';
    // 取得商品分類及商品名稱的數據
    //products_main_type查詢
    $productSql = 'SELECT * from `midterm`.product_main_types';
    $productStmt = $pdo->query($productSql);
    $productRows = $productStmt->fetchAll(PDO::FETCH_NUM);

    //products 查詢
    $productSql2 = 'SELECT * from `midterm`.products';
    $productStmt2 = $pdo->query($productSql2);
    $productRows2 = $productStmt2->fetchAll(PDO::FETCH_NUM);

    // users id name address 查詢
    $productSql3  = 'SELECT user.id,user.name,user.address,user.phone FROM `midterm`.user';
    $productStmt3 = $pdo->query($productSql3);
    $productRows3 = $productStmt3->fetchAll(PDO::FETCH_NUM);

    // 優惠券查詢發送的相關會員id,優惠券id,名稱,折扣金額
    $productSql4  = 'SELECT coupon_send_management.user_id,coupon_send_management.coupon_id,coupon_send_management.coupon_name,coupon.money FROM `midterm`.coupon join coupon_send_management on coupon.id = coupon_send_management.coupon_id';
    $productStmt4 = $pdo->query($productSql4);
    $productRows4 = $productStmt4->fetchAll(PDO::FETCH_NUM);
    ?>



    <?php include '.././parts/html-head.php' ?>
    <style>
      form .mb-3 .form-text {
        color: red;
      }

      /* 自定義商品數量、價格、總價、評價格子的大小 */
      input.product_amount,
      input.price,
      input.productTotalPrices,
      input.product_review_id {
        width: 100px;
      }

      .product_name {
        width: 350px
      }

      .product-select {
        align-items: center;
      }
    </style>
    <div class="d-flex w-100 h-100">

      <?php include  './order-parts/order-html-main.php' ?>
      <?php include  './order-parts/order-navbar-add-edit.php' ?>
      <div class="container">
        <div class="rpw">
          <div class="col-6 w-100">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">新增訂單</h5>

                <form name="form1" onsubmit="sendData(event)">

                  <button id="addBtn" class="btn btn-primary" type="button">新增商品</button>

                  <div class="prdocuts_field" id="productsField">
                    <!-- 新增商品區塊 -->
                  </div>

                  <div class="mb-3">
                    <label for="user_id" class="form-label">會員編號</label>
                    <input type="text" class="form-control" id="user_id" name="user_id">
                    <div class="form-text"></div>
                  </div>
                  <div class="mb-3">
                    <label for="receiver" class="form-label">收件人</label>
                    <input type="text" class="form-control" id="receiver" name="receiver">
                    <div class="form-text"></div>
                  </div>
                  <div class="mb-3">
                    <label for="receiver_address" class="form-label">收件人地址</label>
                    <input type="text" class="form-control" id="receiver_address" name="receiver_address">
                    <div class="form-text"></div>
                  </div>
                  <div class="mb-3">
                    <label for="receiver_phone" class="form-label">收件人電話</label>
                    <input type="text" class="form-control" id="receiver_phone" name="receiver_phone">
                    <div class="form-text"></div>
                  </div>
                  <div class="mb-3">
                    <label for="coupon_id" class="form-label">優惠券名稱</label>
                    <select class="form-select" id="coupon_id" name="coupon_id">
                      <option value="" selected>未使用</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="coupon_discount" class="form-label">優惠券折扣金額</label>
                    <input type="text" class="form-control" id="coupon_discount" name="coupon_discount">
                    <div class="form-text"></div>

                  </div>
                  <div class="mb-3">
                    <label for="shipping_method" class="form-label">運送方式</label>
                    <select class="form-select" id="shipping_method" name="shipping_method">
                      <option value="宅配到家">宅配到家</option>
                      <option value="超商取貨">超商取貨</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="payment_method" class="form-label">付款方式</label>
                    <select class="form-select" id="payment_method" name="payment_method">
                      <option value="信用卡">信用卡</option>
                      <option value="貨到付款">貨到付款</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="total_cost" class="form-label">總金額</label>
                    <input type="text" class="form-control" id="total_cost" name="total_cost" value="">
                    <div class="form-text"></div>
                  </div>
                  <div class="mb-3">
                    <input type="hidden" name="order_status" value="未完成">
                    <label for="order_status" class="form-label">訂單狀態</label>
                    <input type="text" value="未完成" class="form-control" id="order_status" name="order_status" disabled>
                  </div>
                  <button type="submit" class="btn btn-primary">新增訂單</button>
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
              <h1 class="modal-title fs-5">訂單新增結果</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="alert alert-info" role="alert">
                訂單新增成功
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增訂單</button>
              <a href="order-list.php" class="btn btn-primary">跳到訂單列表</a>
            </div>
          </div>
        </div>
      </div>
      </nav>
    </div>

    <div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5">訂單新增結果</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger" role="alert">
              訂單新增失敗
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增訂單</button>
            <a href="order-list.php" class="btn btn-primary">跳到訂單列表</a>
          </div>
        </div>
      </div>
    </div>
    </nav>
    </div>

    <?php include  '.././parts/html-js.php' ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      // 新增商品新增監聽器 動態產生欄位
      document.querySelector('#addBtn').addEventListener('click', () => {
        const productsField = document.querySelector('#productsField')
        const productsSelect = document.querySelector('#productsSelect')
        // 計算產生幾個欄位，並把變數加在id 以及 name 讓每一欄位成為唯一的
        const productCount = productsField.querySelectorAll('.product-select').length;

        const productsHtml = `<div class="d-flex flex-row gap-1 product-select" id="productsSelect${productCount}">
        <button type='button' class="btn btn-danger removeBtn" onclick="removeItem(event)">-</button>
                      <div class="mb-3">
                        <label for="product_types" class="form-label">商品分類</label>
                        <select class="form-select" id="product_types[${productCount}]" name="product_types[${productCount}]">
                          <!-- 商品分類迴圈 -->
                          <option value="" selected>請選擇</option>
                          <?php foreach ($productRows as $r) :
                          ?>
                            <option value="<?= $r[0] ?>"><?= $r[1] ?></option>
                          <?php endforeach ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="product_name" class="form-label">商品名稱</label>
                        <select class="form-select product_name" id="product_name[${productCount}]" name="product_name[${productCount}]">
                          <!-- 商品名稱選項 -->
                          <option value="">請選擇</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="product_size" class="form-label">商品尺寸</label>
                        <select class="form-select" id="product_size[${productCount}]" name="product_size[${productCount}]">
                          <option value="S">S</option>
                          <option value="M">M</option>
                          <option value="L">L</option>
                          <option value="XL">XL</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="product_color" class="form-label">商品顏色</label>
                        <select class="form-select" id="product_color[${productCount}]" name="product_color[${productCount}]">
                          <option value="黑">黑</option>
                          <option value="白">白</option>
                          <option value="象牙色">象牙色</option>
                          <option value="深綠色">深綠色</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="product_amount" class="form-label">商品數量</label>
                        <input type="text" class="form-control product_amount" id="product_amount[${productCount}]" name="product_amount[${productCount}]" value="">
                        <div class="form-text"></div>
                      </div>
                      <div class="mb-3">
                        <label for="price" class="form-label">商品單價</label>
                        <input type="text" class="form-control price" id="price[${productCount}]" name="price[${productCount}]" value="">
                        <div class="form-text"></div>
                      </div>
                      <div class="mb-3">
                        <label for="product_amount_total" class="form-label">商品總價</label>

                        <input type="text" class="form-control productTotalPrices" id="product_amount_total[${productCount}]" name="product_amount_total[${productCount}]" value="">
                        <div class="form-text"></div>`
        const newProductSelect = document.createElement('div')
        newProductSelect.innerHTML = productsHtml

        productsField.appendChild(newProductSelect)
        // \\[${productCount}\\正則表達式拿來轉義，在動態生成的選擇器中使用變數
        const newProductTypesSelect = document.querySelector(`#product_types\\[${productCount}\\]`);
        newProductTypesSelect.addEventListener('change', () => {
          console.log(1234);
          updateProductNames(productCount);
        });

      })

      // 移除整行商品欄位
      function removeItem(event) {
        const element = event.target
        element.closest('.product-select').remove()
      }

      // 輸入會員ID 優惠券篩選會員擁有的優惠券
      document.querySelector('#user_id').addEventListener('keyup', userConnectCoupon)

      function userConnectCoupon() {
        let userID = document.querySelector('#user_id').value
        const fd = new FormData();
        fd.append("type", userID)
        fetch('./order-api./order-coupon-api.php', {
            method: 'POST',
            body: fd
          })
          .then(response => response.json())
          .then(data => {
            if (data) {
              console.log(data);

              let couponDiscount = document.querySelector('#coupon_discount')
              let couponName = document.querySelector('#coupon_id')
              let receiver = document.querySelector('#receiver')
              let receiverPhone = document.querySelector('#receiver_phone')
              let receiverAddress = document.querySelector('#receiver_address')
              couponDiscount.value = ''
              receiver.value = ''
              receiverPhone.value = ''
              receiverAddress.value = ''
              couponName.innerHTML = `<option value="">未使用</option>`; // 清空商品名稱選項

              data.forEach(coupon => {
                let couponOption = document.createElement('option');
                couponOption.value = coupon.coupon_id
                couponOption.textContent = coupon.coupon_name;
                couponName.appendChild(couponOption)
                receiver.value = coupon.name
                receiverPhone.value = coupon.phone
                receiverAddress.value = coupon.address
              })
              couponName.addEventListener("change", (e) => {
                let couponNAME = e.target.value
                const couponFind = data.find((coupon) => {
                  return coupon.coupon_id === Number(couponNAME)
                })
                if (couponFind) {
                  couponDiscount.value = couponFind.money
                } else {
                  couponDiscount.value = 0
                }
              })
            }
          })
      }

      //更改產品名稱，增加產品分類以及金額
      document.addEventListener("DOMContentLoaded", function() {
        document.querySelector("#product_types").addEventListener("change", updateProductNames);
      });
      let sum = 0

      function updateProductNames(productCount) {

        let selectedType = document.querySelector(`#product_types\\[${productCount}\\]`).value;
        const fd = new FormData();
        fd.append("type", selectedType)
        fetch('./order-api./order-products-api.php', {
            method: 'POST',
            body: fd
          })
          .then(response => response.json())
          .then(data => {
            if (data) {
              console.log(data); // 查看從伺服器返回的實際資料

              let productNameSelect = document.querySelector(`#product_name\\[${productCount}\\]`);
              let productPrice = document.querySelector(`#price\\[${productCount}\\]`)
              productPrice.value = ''
              productNameSelect.innerHTML = `<option value="">請選擇</option>`; // 清空商品名稱選項
              const amountTotal = document.querySelector(`#product_amount_total\\[${productCount}\\]`)
              const productAmount = document.querySelector(`#product_amount\\[${productCount}\\]`)
              let couponName = document.querySelector('#coupon_id')
              const totalCost = document.querySelector('#total_cost')

              let couponDiscount = document.querySelector('#coupon_discount')
              // 將獲取到的商品名稱添加到商品名稱
              data.forEach(product => {
                let option = document.createElement('option');
                option.value = product.name;
                option.textContent = product.name;
                productNameSelect.appendChild(option);
              });


              productNameSelect.addEventListener("change", (e) => {
                let productName = e.target.value

                const dataFind = data.find((product) => {
                  return product.name === productName
                })
                productPrice.value = dataFind.price
              })
              //計算總金額
              productAmount.addEventListener("keyup", (e) => {
                sum = 0
                amountTotal.value = Number(productPrice.value * productAmount.value)


                // 商品價格迴圈遞加
                let productTotalPrices = document.querySelectorAll('.productTotalPrices')
                productTotalPrices.forEach(price => {
                  sum += Number(price.value)
                })
                totalCost.value = sum
              })
              couponName.addEventListener("click", (e) => {
                totalCost.value = Number(sum - couponDiscount.value)
              })
            }
          })
          .catch(error => {
            console.error('Error:', error);
          });
      }


      const {
        total_cost: totalcostField,
        receiver: receiverField,
        receiver_address: receiverAddressField,
      } = document.form1


      function sendData(e) {
        // 欄位外觀要回復原來狀態
        totalcostField.style.border = '1px solid #ccc'
        totalcostField.nextElementSibling.innerHTML = ''
        receiverField.style.border = '1px solid #ccc'
        receiverField.nextElementSibling.innerHTML = ''
        receiverAddressField.style.border = '1px solid #ccc'
        receiverAddressField.nextElementSibling.innerHTML = ''
        e.preventDefault(); //不要讓表單以傳統方式送出

        let isPass = true; // 有沒有通過檢查，預設值為 true
        // TODO:檢查資料格式

        if (totalcostField.value.length < 1) {
          isPass = false
          totalcostField.style.border = '2px solid red'
          totalcostField.nextElementSibling.innerHTML = '請輸入總金額'
        }

        if (receiverField.value.length < 2) {
          isPass = false
          receiverField.style.border = '2px solid red'
          receiverField.nextElementSibling.innerHTML = '請輸入收件人姓名'
        }

        if (receiverAddressField.value.length < 2) {
          isPass = false
          receiverAddressField.style.border = '2px solid red'
          receiverAddressField.nextElementSibling.innerHTML = '請輸入收件人地址'
        }

        if (isPass) {
          const fd = new FormData(document.form1); // 沒有外觀的表單，讓前端的表單複製資料給這份沒有外觀表單，把資料向後端丟
          fetch('./order-api./order-add-api.php', {
              method: 'POST',
              body: fd
            }).then(r => r.json())
            .then(result => {

              console.log(result);
              if (result.success) {
                // 資料新增成功
                successModal.show();
              } else {
                // 資料新增失敗
                if (result.error) {
                  failureInfo.innerHTML = result.error
                } else {
                  failureInfo.innerHTML = '訂單新增失敗'
                }
                failureModal.show();
              }
            })
            .catch(ex => {
              console.log(ex);
              failureInfo.innerHTML = '訂單新增失敗' + ex
              failureModal.show();
            })
        }

      }

      const successModal = new bootstrap.Modal('#successModal')
      const failureModal = new bootstrap.Modal('#failureModal')
      const failureInfo = document.querySelector('#failureModal .alert-danger');
    </script>
    <?php include  '.././parts/html-footer.php' ?>