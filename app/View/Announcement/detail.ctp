<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-bell"></i> メッセージ</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <?php if (!empty($announcement)): ?>
          <div class="col-lg-12">
            <div class="col-lg-12 announcement">
              <div class="row">
                <div class="col-lg-12">
                  <h3><?php echo h($announcement['title']); ?></h3>
                  <h4 class="date"><?php echo $this->Html->formatYmdKanji($announcement['date']); ?></h4>
                  <h5 class="date">メッセージID：<?php echo $announcement['announcement_id']; ?></h5>
                </div>
              </div>
              <div class="col-lg-12">
                <?php if ($announcement['category_id'] === ANNOUNCEMENT_CATEGORY_ID_BILLING && 0 < count($billing)) : ?></h3>
                  <div class="row body">
                      <h3 class="notice">ご利用金額（<?php echo $billing[0]['period']; ?>) <?php echo $billing[0]['amount']; ?></h3>
                      <div class="table-responsive">
                          <table class="table">
                              <thead>
                                  <tr>
                                      <th>請求明細</th>
                                      <th>金額（税込）</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($billing as $data) : ?>
                                      <tr>
                                          <td><?php echo $data['detail']; ?></td>
                                          <td><?php echo $data['detail_amount']; ?></td>
                                      </tr>
                                  <?php endforeach; ?>
                              </tbody>
                              <tfoot>
                                  <tr>
                                      <td>合計</td>
                                      <td><?php echo $billing[0]['amount']; ?></td>
                                  </tr>
                              </tfoot>
                          </table>
                      </div>
                  </div>
                <?php else : ?>
                  <div class="row body">
                    <?php echo nl2br(h($announcement['text'])); ?>
                  </div>

                  <?php if (($announcement['category_id'] === ANNOUNCEMENT_CATEGORY_ID_RECEIPT ||
                            $announcement['category_id'] === ANNOUNCEMENT_CATEGORY_ID_KIT_RECEIPT)
                            && !($announcement['category_id'] === ANNOUNCEMENT_CATEGORY_ID_KIT_RECEIPT && !empty($customer->getCorporatePayment()))
                            ) : ?>
                    <div class="row body">
                        <p class="help-block">
                      <?php if ($announcement['category_id'] === ANNOUNCEMENT_CATEGORY_ID_RECEIPT) : ?>
                        ※領収証発行は1回のみ可能です。ボタンを押し、領収証を表示しますと次回以降ボタンが押下できなくなります。<br>
                        お困りの際はお問い合わせページよりご相談下さい。<br>
                        <br>
                      <?php endif; ?>
                        <small>PDFファイルをご覧いただくにはAdobe Readerが必要です。<br>
                        Adobe Readerがインストールされていない場合は、下のアイコンをクリックして、ダウンロードした後インストールしてください。<br>
                        Adobe ReaderをインストールするとPDFファイルがご覧いただけます。<br>
                        詳しくは、 アドビ システムズ社のサイト をご覧ください。<br>
                        ※ご提供するデータは、細心の注意を払って掲載しておりますが、その正確性を完全に保証するものではありません。<br>
                        ※これら各種データをご利用になったことによって、生ずる一切の損害についても、責任を負うものではありません。<br>
                        ※当データの著作権は寺田倉庫株式会社に帰属するものです。</small><br>
                        <br>
                        <a href="http://get.adobe.com/jp/reader/" target="_blank">
                            <img style="width: auto" src="/images/acrobat_reader.png">
                        </a>
                        </p>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-primary btn-lg btn-block" href="/announcement/">メッセージ一覧に戻る</a>
            </span>
            <?php if (in_array($announcement['category_id'], ANNOUNCEMENT_CATEGORY_YAMATO)) : ?>
                <?php if (isset($pickup_yamato_change) && $pickup_yamato_change) : ?>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-danger btn-lg btn-block" href="/pickup/edit/<?php echo $pickup_yamato['pickup_yamato_id']; ?>">集荷情報を変更する</a>
                  <p class="text-center text-alert">集荷情報は<span><?php echo $pickup_yamato_change['change_date']; ?>時</span>まで変更可能です。</p>
                </span>
                <?php else : ?>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-danger btn-lg btn-block" disabled>集荷情報を変更する</a>
                  <p class="text-center text-alert"><span>集荷情報の変更可能時間が過ぎているため、変更できません。<br>
                  ヤマト運輸のサポートセンターに直接ご連絡ください<br>
                  <a href="http://www.kuronekoyamato.co.jp/ytc/center/servicecenter_list.html" target="_blank">サービスセンター一覧（外部サイト）</a></span></p>
                  </span>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ((($announcement['category_id'] === ANNOUNCEMENT_CATEGORY_ID_RECEIPT ||
                      $announcement['category_id'] === ANNOUNCEMENT_CATEGORY_ID_KIT_RECEIPT)
                      && !($announcement['category_id'] === ANNOUNCEMENT_CATEGORY_ID_KIT_RECEIPT && !empty($customer->getCorporatePayment())))
                      || $outbound_receipt_flag) : ?>
            <?php echo $this->Form->create('Box', ['url' => '/announcement/'.$announcement['announcement_id'].'/receipt', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
            <span class="col-lg-12 col-md-12 col-xs-12">
                <button type="submit" class="btn btn-danger btn-lg btn-block submit_after_restore js-none_loader">領収証発行</button>
            </span>
            <?php echo $this->Form->end(); ?>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
