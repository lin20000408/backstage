<div class="row">
  <div class="col">
    <table class="table table-bordered table-striped">
      <thead>
        <tr style="white-space:nowrap;">
          <th><i class="fa-solid fa-trash"></i></th>
          <th>管理員編號</th>
          <th>管理員</th>
          <th>帳號</th>
          <th>密碼</th>
          <th>信箱</th>
          <th>照片</th>
          <th>更新時間</th>
          <th><i class="fa-solid fa-file-pen"></i></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td>
              <a href="javascript: deleteOne(<?= $r['id'] ?>)">
                <i class="fa-solid fa-trash"></i>
              </a>
            </td>
            <td>
              <?= $r['id'] ?>
            </td>
            <td>
              <?= htmlentities($r['adminName']) ?>
            </td>
            <td>
              <?= htmlentities($r['account']) ?>
            </td>
            <td>
              <?= htmlentities($r['password']) ?>
            </td>
            <td>
              <?= htmlentities($r['email']) ?>
            </td>
            <td>
              <img src=<?= htmlentities($r['picture']) ?> style="height:50px;"  alt="">
            </td>
            <td>
              <?= htmlentities($r['time']) ?>
            </td>
            <td>
              <a href="edit-admin.php?id=<?= $r['id'] ?>">
                <i class="fa-solid fa-file-pen"></i>
              </a>
            </td>
          </tr>
        <?php endforeach ?>

      </tbody>
    </table>
  </div>
</div>