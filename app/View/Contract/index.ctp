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
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>契約情報一覧</h2>
                <div class="col-lg-12 col-xs-12 agreement">
                  <div class="form-group col-lg-12">
                    <?php $productCdList = [PRODUCT_CD_MONO, PRODUCT_CD_HAKO, PRODUCT_CD_CARGO_JIBUN, PRODUCT_CD_CARGO_HITOMAKASE, PRODUCT_CD_CLEANING_PACK, PRODUCT_CD_SHOES_PACK]; ?>
                    <?php foreach($productCdList as $productCd) : ?>
                    <div class="row list">
                      <div class="col-xs-12 col-md-10 col-lg-10">
                        <?php echo PRODUCT_NAME[$productCd]; ?>
                      </div>
                      <div class="col-xs-12 col-md-2 col-lg-2">
                        <?php echo Hash::get($product_summary, $productCd, 0); ?>箱
                      </div>
                    </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>ポイント</h2>
                <p class="form-control-point col-lg-12">使えば使うほどたまる minikura ポイント。<br />
                  たまったポイントはオプションや取り出し送料に使うことができます<br />
                  <a href="<?php echo Configure::read('site.static_content_url'); ?>/lineup/points.html" class="animsition-link">▶minikuraポイントについて</a>
                </p>
                <div class="form-group col-lg-12">
                  ただいま <span class="point"><?php echo $point['point_balance']; ?></span> ポイント
                  <p class="help-block">※ポイントのご利用は獲得日から2年間有効です。</p>
                  <p class="help-block">※ポイントは100ポイント以上の残高かつ10ポイント単位からのご利用となります。</p>
                </div>
                <h2>ポイント履歴</h2>
                <p class="form-control-point col-lg-12">
                  <a href="/point" class="animsition-link">▶ポイント履歴</a>
                </p>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
