    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-list-alt"></i> ポイント</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>ポイント履歴</h2>
                <div class="form-group col-lg-12">
                  <p></p>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>発行日・消費日</th>
                          <th>内容</th>
                          <th>ポイント</th>
                          <th>有効期限</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($histories as $history): ?>
                        <?php if (!empty($history['added_datetime'])) : ?>
                        <tr>
                          <td><?php echo empty($history['added_datetime']) ? '' : date('Y/m/d', strtotime($history['added_datetime'])); ?></td>
                          <td><?php echo Hash::get(POINT_TYPE, $history['point_type']); ?></td>
                          <td><?php echo $history['added_point']; ?></td>
                          <td><?php echo empty($history['expire_datetime']) ? '' : date('Y/m/d', strtotime($history['expire_datetime'])); ?></td>
                        </tr>
                        <?php else : ?>
                        <tr>
                          <td><?php echo empty($history['used_datetime']) ? '' : date('Y/m/d', strtotime($history['used_datetime'])); ?></td>
                          <td><?php echo Hash::get(POINT_TYPE, $history['point_type']); ?></td>
                          <td><?php echo $history['used_point']; ?></td>
                          <td><?php echo empty($history['expire_datetime']) ? '' : date('Y/m/d', strtotime($history['expire_datetime'])); ?></td>
                        </tr>
                        <?php endif; ?>
                      <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                  <?php echo $this->element('paginator'); ?>
                </div>
                <span class="col-lg-12 col-md-12 col-xs-12">
                <a class="btn btn-primary btn-lg btn-block animsition-link" href="/contract">戻る</a>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
