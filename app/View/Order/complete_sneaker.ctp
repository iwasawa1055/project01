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
                <?php echo $this->element('Order/breadcrumb_list'); ?>
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
            <section class="dsn-nextback fix"><a href="/" class="dsn-btn-next-full">マイページトップへ進む <i class="fa fa-chevron-circle-right"></i></a>
            </section>
        </div>
    </div>
