<?php $this->Html->script('direct_inbound/input.js', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('https://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>
<!-- 暫定的にFirstOrderのcssを読み込み -->
<?php $this->Html->css('/css/app.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/direct_inbound/dsn-boxless.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/direct_inbound/direct_inbound_dev.css', ['block' => 'css']); ?>
<?php $this->validationErrors['OrderKit'] = $validErrors; ?>

    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i>預け入れ</h1>
      </div>
    </div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <h2>minikuraダイレクト</h2>
            <p class="form-control-static col-lg-12">預け入れ申し込みが完了しました。<br>
              預け入れ申し込みありがとうございました。</p>
                <span class="col-lg-12 col-md-12 col-xs-12">
                <a class="btn btn-danger btn-lg btn-block" href="/">マイページへ戻る</a>
                </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

