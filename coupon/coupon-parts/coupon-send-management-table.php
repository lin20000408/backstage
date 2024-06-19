<div class="row">
    <div class="col">
        <div aria-label="Page navigation example" class="d-flex justify-content-between mb-3">

            <div class="d-flex">
                <!-- 發送優惠劵按鈕 -->
                <a href="coupon-send.php">
                    <button type="button" class="btn btn-primary text-white">發送優惠劵</button>
                </a>

                <!-- 匯出Excel -->
                <form method="post" action="coupon-excel.php">
                    <input type="submit" name="excel" value="匯出Excel" class="btn  btn-info text-white ms-3" />
                </form>
            </div>
            
            <form action="coupon-send-management.php" class="d-flex h-75" role="search">
                <!-- 搜索下拉菜单 -->
                <select id="searchField" name="searchField">

                    <!-- selected是option的屬性，选项是否默認被选中 -->
                    <option value="coupon_name" <?php if ($searchField === 'coupon_name') echo 'selected'; ?>>優惠劵名稱</option>
                    <option value="usage_status" <?php if ($searchField === 'usage_status') echo 'selected'; ?>>使用狀況</option>
                    <option value="usage_time" <?php if ($searchField === 'usage_time') echo 'selected'; ?>>使用時間</option>
                    <option value="send_time" <?php if ($searchField === 'send_time') echo 'selected'; ?>>發送時間</option>
                </select>

                <!-- 搜索内容 -->
                <!-- isset($_GET['keyword']) 检查是否存在名为 "keyword" 的 URL 参数。如果用户通过 GET 请求传递了名为 "keyword" 的参数，则条件成立，表示用户已经输入了搜索关键字。 -->
                <!-- htmlspecialchars($_GET['keyword'], ENT_QUOTES) 这部分是用于将从 URL 参数中获取的搜索关键字进行 HTML 转义，以防止 XSS（跨站脚本攻击）攻击。 -->
                <!-- htmlspecialchars 函数将 HTML 特殊字符转换为对应的 HTML 实体。 -->
                <!-- ENT_QUOTES 参数表示同时转义单引号和双引号。 -->
                <input class="form-control-sm mx-2" id="searchInput" type="text" name="keyword" placeholder="搜尋" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword'], ENT_QUOTES) : ''; ?>" />
                <button class="btn btn-outline-primary" type="submit">
                    搜尋
                </button>
            </form>
        </div>
    </div>
</div>
<form action="coupon-send-management.php">
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center"><i class="fa-solid fa-trash-can"></i></th>
                        <th>優惠劵編號</th>
                        <th>優惠劵名稱</th>
                        <th>折抵金額</th>
                        <th>會員編號</th>
                        <th>會員姓名</th>
                        <th>使用狀況</th>
                        <th>使用時間</th>
                        <th>發送時間</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <!-- 垃圾桶icon -->
                            <td>
                                <!-- 呼叫假連結要刪哪一筆資料，透過send_time -->
                                <a href="javascript: deleteOne(`<?= $r['send_time'] ?>`)">
                                    <i class="fa-solid fa-trash-can d-flex justify-content-center"></i>
                                </a>
                            </td>

                            <td><?= $r['coupon_id'] ?></td>
                            <td><?= $r['coupon_name'] ?></td>
                            <td><?= $r['coupon_money'] ?></td>
                            <td><?= $r['user_id'] ?></td>
                            <td><?= $r['user_name'] ?></td>
                            <td><?= $r['usage_status'] ?></td>
                            <td><?= $r['usage_time'] ?></td>
                            <td><?= $r['send_time'] ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</form>
<div class="row">
    <div class="col">
        <div aria-label="Page navigation example" class="d-flex justify-content-center">
            <ul class="pagination">

                <!-- 雙左箭頭 -->
                <!-- 第一頁時就無效果 -->
                <li class="page-item <?= $page == 1 || empty($rows) ? 'disabled' : '' ?>">

                    <!-- 換頁時還能保留搜尋關鍵字 -->
                    <a class="page-link" href="?page=1&keyword=<?= urlencode($searchKeyword) ?>&searchField=<?= $searchField ?>">
                        <i class="fa-solid fa-angles-left"></i>
                    </a>
                </li>
                <!-- 左箭頭 -->
                <!-- 第一頁時就無效果 -->
                <li class="page-item <?= $page == 1 || empty($rows) ? 'disabled' : '' ?>">

                    <!-- 換頁時還能保留搜尋關鍵字 -->
                    <a class="page-link" href="?page=<?= $page - 1 ?>&keyword=<?= urlencode($searchKeyword) ?>&searchField=<?= $searchField ?>">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                </li>

                <!-- 前後各2頁 -->
                <?php for ($i = $page - 2; $i <= $page + 2; $i++) : ?>

                    <!-- 第一頁以下沒有,最後一頁之後也沒有 -->
                    <?php if ($i >= 1 && $i <= $totalPage) : ?>

                        <!-- 反白(變藍色) -->
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">

                            <!-- 換頁時還能保留搜尋關鍵字 -->
                            <a class="page-link" href="?page=<?= $i ?>&keyword=<?= urlencode($searchKeyword) ?>&searchField=<?= $searchField ?>"><?= $i ?></a>
                        </li>
                    <?php endif ?>
                <?php endfor ?>

                <!-- 右箭頭 -->
                <!-- 最後一頁時就無效果 -->
                <li class="page-item <?= $page == $totalPage || empty($rows) ? 'disabled' : '' ?>">

                    <!-- 換頁時還能保留搜尋關鍵字 -->
                    <a class="page-link" href="?page=<?= $page + 1 ?>&keyword=<?= urlencode($searchKeyword) ?>&searchField=<?= $searchField ?>">
                        <i class="fa-solid fa-angle-right"></i>
                    </a>
                </li>
                <!-- 雙右箭頭 -->
                <!-- 最後一頁時就無效果 -->
                <li class="page-item <?= $page == $totalPage || empty($rows) ? 'disabled' : '' ?>">

                    <!-- 換頁時還能保留搜尋關鍵字 -->
                    <a class="page-link" href="?page=<?= $totalPage ?>&keyword=<?= urlencode($searchKeyword) ?>&searchField=<?= $searchField ?>">
                        <i class="fa-solid fa-angles-right"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>