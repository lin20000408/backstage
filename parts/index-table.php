<div class="row">
          <div class="col">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>username</th>
                  <th>account</th>
                  <th>password</th>
                  <th>mobile</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $r) : ?>
                  <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= $r['username'] ?></td>
                    <td><?= $r['account'] ?></td>
                    <td><?= $r['password'] ?></td>
                    <td><?= $r['mobile'] ?></td>                  
                  </tr>
                <?php endforeach ?>

              </tbody>
            </table>
          </div>
        </div>