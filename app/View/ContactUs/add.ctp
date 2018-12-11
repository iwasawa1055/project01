    <?php if (!empty($validErrors)) { $this->validationErrors['ZendeskContactUs'] = $validErrors; } ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-pencil-square-o"></i> お問い合わせ</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create('ZendeskContactUs', ['url' => ['controller' => 'contact_us', 'action' => 'confirm', 'id' => $id], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12 none-title">

                <!-- エントリーユーザー向け -->
                <?php if ($customer->isEntry()) : ?>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ZendeskContactUs.lastname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓', 'error' => false]); ?>
                  <?php echo $this->Form->error('ZendeskContactUs.lastname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ZendeskContactUs.lastname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('ZendeskContactUs.lastname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ZendeskContactUs.firstname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名', 'error' => false]); ?>
                  <?php echo $this->Form->error('ZendeskContactUs.firstname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ZendeskContactUs.firstname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('ZendeskContactUs.firstname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <?php endif; ?>
                <!-- エントリーユーザー向け end -->

                <div class="form-group col-lg-12">
                  <label>お問い合わせの種別</label>
                  <?php echo $this->Form->select('ZendeskContactUs.division', CONTACTUS_DIVISION, ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                  <?php echo $this->Form->error('ZendeskContactUs.division', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの内容</label>
                  <?php echo $this->Form->textarea('ZendeskContactUs.comment', ['class' => "form-control", 'rows' => 5, 'error' => false]); ?>
                  <?php echo $this->Form->error('ZendeskContactUs.comment', null, ['wrap' => 'p']) ?>
                </div>

                <div id="bug_area">
                  <div class="form-group col-lg-12">
                    <label>不具合発生日時</label>
                    <?php echo $this->Form->input('ZendeskContactUs.bug_datetime', ['class' => "form-control", 'error' => false, 'placeholder' => '例）2020/1/1 13:00 または 2020年1月1日 13時頃 など']); ?>
                    <?php echo $this->Form->error('ZendeskContactUs.bug_datetime', null, ['wrap' => 'p']) ?>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>不具合発生URL（またはページ）</label>
                    <?php echo $this->Form->input('ZendeskContactUs.bug_url', ['class' => "form-control", 'error' => false, 'placeholder' => '例）https://mypage.minikura.com/login または ログインページ など']); ?>
                    <?php echo $this->Form->error('ZendeskContactUs.bug_url', null, ['wrap' => 'p']) ?>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>ご利用環境（OS・ブラウザ）</label>
                    <?php echo $this->Form->input('ZendeskContactUs.bug_environment', ['class' => "form-control", 'error' => false, 'placeholder' => '例）iOS9・Safari など']); ?>
                    <?php echo $this->Form->error('ZendeskContactUs.bug_environment', null, ['wrap' => 'p']) ?>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>具体的な操作と症状</label>
                    <?php echo $this->Form->textarea('ZendeskContactUs.bug_text', ['class' => "form-control", 'rows' => 5, 'error' => false]); ?>
                    <?php echo $this->Form->error('ZendeskContactUs.bug_text', null, ['wrap' => 'p']) ?>
                  </div>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                    <a class="btn btn-primary btn-lg btn-block" href="/contact_us/index">戻る</a>
                </span>
                <span class="col-lg-6 col-md-12 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">確認する</button>
                </span>
                <?php if (!empty($ticket_id)):?>
                    <?php echo $this->Form->hidden('ZendeskContactUs.ticket_id', ['value' => $ticket_id]); ?>
                <?php endif;?>

                <?php if ($id) : ?>
                <div class="col-lg-12 col-md-12 col-xs-12">
                  <h3 class="col-lg-12"><?php echo h($announcement['title']); ?></h3>
                  <h4 class="date col-lg-12"><?php echo $this->Html->formatYmdKanji($announcement['date']); ?></h4>
                  <h5 class="date col-lg-12">メッセージID：<?php echo $announcement['announcement_id']; ?></h5>
                  <div class="col-lg-12">
                  <?php if ($announcement['category_id'] === ANNOUNCEMENT_CATEGORY_ID_BILLING && 0 < count($announcement['billing'])) : ?>
                  <div class="row body">
                      <h3 class="notice">ご利用金額（<?php echo $announcement['billing'][0]['period']; ?>) <?php echo $announcement['billing'][0]['amount']; ?></h3>
                      <div class="table-responsive">
                          <table class="table">
                              <thead>
                                  <tr>
                                      <th>請求明細</th>
                                      <th>金額（税込）</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($announcement['billing'] as $data) : ?>
                                      <tr>
                                          <td><?php echo $data['detail']; ?></td>
                                          <td><?php echo $data['detail_amount']; ?></td>
                                      </tr>
                                  <?php endforeach; ?>
                              </tbody>
                              <tfoot>
                                  <tr>
                                      <td>合計</td>
                                      <td><?php echo $announcement['billing'][0]['amount']; ?></td>
                                  </tr>
                              </tfoot>
                          </table>
                      </div>
                  </div>
                  <?php else:?>
                  <div class="row body">
                      <?php echo nl2br(h($announcement['text'])); ?>
                  </div>
                  <?php endif;?>
                  </div>
                </div>
              <?php endif; ?>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
