    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-list-alt"></i> 会員情報</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>お客さま情報</h2>
              <?php if (!empty($data)) : ?>
                <?php if ($data['applying']): ?>
                  <p class="form-control-static col-lg-12">変更申請中です。<br />
                    変更内容を確認させていただきますので、変更の反映にはお時間をいただきます。<br />
                    ※変更内容によっては確認のご連絡をさせていただく場合がございます。あらかじめご了承ください。</p>
                <?php endif; ?>
              <?php if ($customer->isPrivateCustomer()) : ?>
              <?php // 個人 ?>
                <div class="form-group col-lg-12">
                  <label>郵便番号</label>
                  <p><?php echo $data['postal']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>住所</label>
                  <p><?php echo h($data['pref'].$data['address1'].$data['address2'].$data['address3']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>電話番号</label>
                  <p><?php echo $data['tel1']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>お名前</label>
                  <p class="form-control-static"><?php echo h($data['lastname'] . '　' . $data['firstname']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>お名前（カナ）</label>
                  <p><?php echo h($data['lastname_kana'] . '　' . $data['firstname_kana']); ?></p>
                </div>
              <?php else : ?>
              <?php // 法人 ?>
                <div class="form-group col-lg-12">
                  <label>郵便番号</label>
                  <p><?php echo $data['postal']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>住所</label>
                  <p><?php echo h($data['pref'].$data['address1'].$data['address2'].$data['address3']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>電話番号</label>
                  <p><?php echo $data['tel1']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>会社名</label>
                  <p class="form-control-static"><?php echo h($data['company_name']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>会社名（カナ）</label>
                  <p class="form-control-static"><?php echo h($data['company_name_kana']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>担当者名</label>
                  <p class="form-control-static"><?php echo h($data['staff_name']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>担当者名（カナ）</label>
                  <p class="form-control-static"><?php echo h($data['staff_name_kana']); ?></p>
                </div>
              <?php endif; ?>
                <?php if (!$data['applying']): ?>
                <div class="col-lg-12 col-md-12 col-xs-12">
                  <a class="btn btn-info btn-md pull-right" href="/customer/info/edit">情報を変更する</a>
                </div>
                <?php endif; ?>
              <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <?php if (! $customer->isPrivateCustomer()) : ?>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>お支払方法</h2>
                <div class="form-group col-lg-12">
				  <?php if ($customer->getInfo()['account_situation']):?>
		            <p><?php echo h(CORPORATE_PAYMENT_METHOD[$customer->getInfo()['account_situation']]);?></p>
				  <?php else:?>
		            <p><?php echo CORPORATE_PAYMENT_METHOD['credit_card'];?></p>
				  <?php endif;?>
                </div>
              </div>
            </div>
          </div>
        </div>
		<?php endif;?>
      </div><!--col-lg-12-->
    </div><!--row-->
