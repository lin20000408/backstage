<div class="row">
    <div class="col">
        <div aria-label="Page navigation example" class="d-flex justify-content-end mb-3">
            <form action="coupon.php" class="d-flex h-75" role="search">
                <!-- 搜索下拉菜单 -->
                <select id="searchField" name="searchField">

                    <!-- selected是option的屬性，选项是否默認被选中 -->
                    <option value="name" <?php if ($searchField === 'name') echo 'selected'; ?>>優惠劵名稱</option>
                    <option value="money" <?php if ($searchField === 'money') echo 'selected'; ?>>折抵金額</option>
                    <option value="starting_time" <?php if ($searchField === 'starting_time') echo 'selected'; ?>>開始日期</option>
                    <option value="end_time" <?php if ($searchField === 'end_time') echo 'selected'; ?>>結束日期</option>
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
<form action="coupon.php">
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>編號</th>
                        <th>優惠劵名稱</th>
                        <th>折抵金額</th>
                        <th>開始日期</th>
                        <th>結束日期</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><?= $r['name'] ?></td>
                            <td><?= $r['money'] ?></td>
                            <td><?= $r['starting_time'] ?></td>
                            <td><?= $r['end_time'] ?></td>
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
                    <!-- urlencode() 在 URL 中传递包含特殊字符的搜索关键字，如空格、问号、等号等，使用 urlencode() 函数对其进行 URL 编码，以确保这些字符在 URL 中不会引起混淆或破坏 URL 结构。 -->
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