<div class="row">
  <div class="col">
    <div aria-label="Page navigation example" class="d-flex justify-content-end mb-3">
      <form action="" class="d-flex h-75" role="search" name="searchForm">
        <!-- 搜索下拉菜单 -->
        <select id="searchField" name="searchField">
          <!-- selected是option的屬性，选项是否默認被选中 -->
          <option value="">所有文章</option>
          <?php foreach ($chooseType as $r) : ?>
            <option value="<?= $r[0] ?>" <?php echo ($_GET['searchField'] ?? '') === $r[0] ? 'selected' : ''; ?>><?= $r[0] ?></option>
          <?php endforeach ?>
        </select>
        <input class="form-control-sm mx-2" id="searchInput" type="text" name="keyword" placeholder="搜尋" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword'], ENT_QUOTES) : ''; ?>" />
        <button class="btn btn-outline-primary" type="submit">
          搜尋
        </button>
      </form>
    </div>
  </div>
</div>

<div class="row">
  <div class="col">
    <table class="table table-bordered table-striped " id="dataTable">
      <thead class="text-center">
        <tr>

          <th class="text-center col"><i class="fa-solid fa-trash-can"></i></th>
          <th class="text-center col">編號</th>
          <th class="text-center col">發文者編號</th>
          <th class="text-center col">發文者名稱</th>
          <th class="text-center col-1">類型</th>
          <th class="text-center col-2">標題</th>
          <th class="text-center col-3">內容</th>
          <th class="text-center col">照片</th>
          <th class="text-center col">按讚數</th>
          <th class="text-center col">發文時間</th>
          <th class="text-center"><i class="fa-solid fa-file-pen"></i></th>

        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r) : ?>

          <tr>
            <!-- 呼叫假連結要刪哪一筆資料，透過id -->
            <td><a href="javascript: deleteOne(<?= $r['id'] ?>)"><!-- 垃圾桶icon -->
                <i class="fa-solid fa-trash-can d-flex justify-content-center"></i>
              </a></td>
            <td class="text-center"><?= $r['id'] ?></td>
            <td class="text-center"><?= $r['user_id'] ?></td>
            <td class="text-center"><?= $r['user_name'] ?></td>
            <td class="text-center"><?= $r['type'] ?></td>
            <td style="max-width: 50px;" class="text-truncate"><?= $r['head'] ?></td>
            <td style="max-width: 150px;" class="text-truncate"><?= $r['content'] ?></td>
            <td><img src="<?= $r['picture'] ?>" alt="" width="39px"></td>
            <td class="text-center"><?= $r['likes'] ?></td>
            <td class="text-start"><?= $r['time'] ?></td>
            <!-- 編輯icon -->
            <td><a href="./forum-edit.php?id=<?= $r['id'] ?>">
                <i class="fa-solid fa-file-pen d-flex justify-content-center"></i>
              </a></td>
          </tr>

        <?php endforeach ?>

      </tbody>
    </table>
  </div>
</div>
<div class="row ">
  <div class="col d-flex justify-content-center">
    <div aria-label="Page navigation example">
      <ul class="pagination">
        <!-- 雙左箭頭 -->
        <!-- 第一頁時就無效果 -->
        <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">

          <a class="page-link" href="?page=1&searchField=<?= urlencode($searchField) ?>&keyword=<?= urlencode($searchKeyword) ?>">
            <i class="fa-solid fa-angles-left"></i>
          </a>
        </li>
        <!-- 左箭頭 -->
        <!-- 第一頁時就無效果 -->
        <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">

          <a class="page-link" href="?page=<?= $page - 1 ?>&searchField=<?= urlencode($searchField) ?>&keyword=<?= urlencode($searchKeyword) ?>">
            <i class="fa-solid fa-angle-left"></i>
          </a>
        </li>

        <!-- 前後各2頁 -->
        <?php for ($i = $page - 2; $i <= $page + 2; $i++) : ?>
          <?php if ($i >= 1 and $i <= $totalPage) : ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>&searchField=<?= urlencode($searchField) ?>&keyword=<?= urlencode($searchKeyword) ?>">
                <?= $i ?></a>
            </li>
          <?php endif ?>
        <?php endfor ?>
        <!-- 右箭頭 -->
        <!-- 最後一頁時就無效果 -->
        <li class="page-item <?= $page == $totalPage ? 'disabled' : '' ?>">

          <a class="page-link" href="?page=<?= $page + 1 ?>&searchField=<?= urlencode($searchField) ?>&keyword=<?= urlencode($searchKeyword) ?>">
            <i class="fa-solid fa-angle-right"></i>
          </a>
        </li>
        <!-- 雙右箭頭 -->
        <!-- 最後一頁時就無效果 -->
        <li class="page-item <?= $page == $totalPage ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $totalPage ?>&searchField=<?= urlencode($searchField) ?>&keyword=<?= urlencode($searchKeyword) ?>">
            <i class="fa-solid fa-angles-right"></i>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>