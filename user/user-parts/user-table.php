<div class="row">
  <div class="col">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>會員編號</th>
          <th>帳號</th>
          <th>密碼</th>
          <th>名字</th>
          <th>性別</th>
          <th>出生日</th>
          <th>信箱</th>
          <th>手機</th>
          <th>地址</th>
          <th>身高</th>
          <th>胸圍</th>
          <th>腰圍</th>
          <th>臀圍</th>
          <!-- <th>avatar</th> -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td>
              <?= $r['id'] ?>
            </td>
            <td>
              <?= htmlentities($r['account']) ?>
            </td>
            <td>
              <?= htmlentities($r['password']) ?>
            </td>
            <td>
              <?= htmlentities($r['name']) ?>
            </td>
            <td>
              <?= htmlentities($r['gender']) ?>
            </td>
            <td>
              <?= htmlentities($r['birth_date']) ?>
            </td>
            <td>
              <?= htmlentities($r['email']) ?>
            </td>
            <td>
              <?= htmlentities($r['phone']) ?>
            </td>
            <td>
              <?= htmlentities($r['address']) ?>
            </td>
            <td>
              <?= htmlentities($r['height']) ?>
            </td>
            <td>
              <?= htmlentities($r['chest_size']) ?>
            </td>
            <td>
              <?= htmlentities($r['waist_size']) ?>
            </td>
            <td>
              <?= htmlentities($r['hips_size']) ?>
            </td>
            <!-- <td>
              <?= htmlentities($r['avatar']) ?>
            </td> -->
          </tr>
        <?php endforeach ?>

      </tbody>
    </table>
  </div>
</div>