    <?php if(isset($point_balance_error)) : ?><p class="error-message"><?php echo $point_balance_error; ?></p><?php endif; ?>
    <?php if(isset($point_history_error)) : ?><p class="error-message"><?php echo $point_history_error; ?></p><?php endif; ?>
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
                <h2>ポイント</h2>
                <div class="form-group col-lg-12">
                  <?php if(isset($point['point_balance'])) : ?>
                  ただいま <span class="point"><?php echo $point['point_balance']; ?></span> ポイント
                  <?php endif; ?>
                  <p class="help-block">※ポイントのご利用は獲得日から2年間有効です。</p>
                  <p class="help-block">※ポイントは100ポイント以上の残高かつ10ポイント単位からのご利用となります。</p>
                </div>
				<?php if (! empty($histories)):?>
                <h2>ポイント履歴</h2>
                <p></p>
                <div class="form-group col-lg-12">
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
                          <td><?php echo $this->Html->formatPointType($history) ?></td>
                          <td><?php echo $history['added_point']; ?></td>
                          <td><?php echo empty($history['expire_datetime']) ? '' : date('Y/m/d', strtotime($history['expire_datetime'])); ?></td>
                        </tr>
                        <?php else : ?>
                        <tr>
                          <td><?php echo empty($history['used_datetime']) ? '' : date('Y/m/d', strtotime($history['used_datetime'])); ?></td>
                          <td>
                              <?php echo Hash::get(POINT_TYPE, $history['point_type']); ?>
                          </td>
                          <td><?php echo '-' . $history['used_point']; ?><?php if ($history['status'] === POINT_STATUS_CANCEL) : ?>(キャンセル)<?php endif; ?></td>
                          <td><?php echo empty($history['expire_datetime']) ? '' : date('Y/m/d', strtotime($history['expire_datetime'])); ?></td>
                        </tr>
                        <?php endif; ?>
                      <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                  <?php echo $this->element('paginator'); ?>
                </div>
				<?php endif;?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
