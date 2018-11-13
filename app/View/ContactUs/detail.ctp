   <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fa fa-pencil-square-o"></i> お問い合わせ</h1>
        </div>
   </div>
   <?php echo $this->Form->error('ZendeskContactUs.comment', null, ['wrap' => 'p']); ?>
   <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create('ContactUs', ['url' => ['controller' => 'contact_us', 'action' => 'detail', 'inputDefaults' => ['label' => false, 'div' => false]], 'novalidate' => true]); ?>
              <div class="col-lg-12 none-title">
                <div class="form-group col-lg-12">
                  <label>お問い合わせの種別
                      <?php if ($ticket_data['status'] === 'solved'):?>
                      <span class="btn btn-success btn-xs dev_contact_us_margin">回答済み</span>
                      <?php else:?>
                      <span class="btn btn-danger btn-xs dev_contact_us_margin">回答待ち</a>
                      <?php endif;?>
                  </label>
                  <p class="form-control-static"><?php echo $ticket_data['subject'];?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの内容</label>
                  <p class="form-control-static"><?php echo nl2br($ticket_data['description']);?></p>
                </div>
                <ul class="col-lg-12 history">
                <?php foreach ($comment_data as $key): ?>
                  <?php if ($key['public'] === true): ?>
                  <li>
                    <p class="name"><?php echo $ticket_data['requester_id'] == $key['author_id'] ? 'お客様' : 'minikura運営事務局'; ?></p>
                    <p class="data"><?php echo date('Y-m-d H:i', strtotime($key['created_at']));?></p>
                    <p><?php echo nl2br($key['body']);?></p>
                  </li>
                  <?php endif; ?>
                <?php endforeach; ?>
                </ul>
                <div class="form-group col-lg-12">
                  <label>メッセージを送信</label>
                  <?php echo $this->Form->textarea('ZendeskContactUs.comment', ['class' => "form-control", 'rows' => 5, 'error' => false, 'placeholder' => 'お問い合わせ内容を入力してください']); ?>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-primary btn-lg btn-block" href="/contact_us">戻る</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <button type="submit" class="btn btn-danger btn-lg btn-block">この内容を送信する</button>
                </span>
                </div>
              </div>
            <?php echo $this->Form->hidden('ZendeskContactUs.ticket_id', ['value' => $ticket_data['id']]); ?>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
