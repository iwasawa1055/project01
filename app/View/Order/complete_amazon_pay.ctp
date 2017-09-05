<?php $this->Html->css('/css/order/dsn-purchase.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/order/order_dev.css', ['block' => 'css']); ?>


<div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>
      </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <section id="dsn-pagenation">
                  <ul>
                      <li>
                          <i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
                      </li>
                      <li>
                          <i class="fa fa-check"></i><span>確認</span>
                      </li>
                      <li class="dsn-on">
                          <i class="fa fa-truck"></i><span>完了</span>
                      </li>
                  </ul>
                </section>
                <section id="dsn-adress" class="dsn-complete">
                    <h2>注文が完了しました。</h2>
                    <div class="dsn-wrapper">
                        <div class="dsn-form">
                            <p class="dsn-dialog">専用ボックスのご注文ありがとうございました。</p>
                            <p class="dsn-dialog">ご登録メールアドレスにお送りしました完了メールをご確認ください。</p>
                        </div>
                    </div>
                </section>
            </div>
            <section class="dsn-nextback fix dev-forefront"><a href="/" class="dsn-btn-next-full">マイページトップへ進む <i class="fa fa-chevron-circle-right"></i></a>
            </section>
        </div>
    </div>
