    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-tag"></i> ご利用中のサービス</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
          <?php echo $this->Form->create('Box', ['url' => '/box/detail/'.$box['box_id'].'/edit', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
            <div class="row">
              <div class="col-lg-12">
                <h2><?php echo $box['product_name']; ?></h2>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="panel panel-default">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                      <h3>
                        <?php echo $this->Form->input('Box.box_name', ['class' => "form-control", 'error' => false]); ?>
                      </h3>
                      <?php echo $this->Form->error('Box.box_name', null, ['wrap' => 'p']) ?>
                      <p class="box-list-caption"><span>写真撮影</span>あり</p>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                      <?php echo $this->Form->textarea('Box.box_note', ['class' => "form-control box_note", 'rows' => 5, 'error' => false]); ?>
                      <?php echo $this->Form->error('Box.box_note', null, ['wrap' => 'p']) ?>
                    </div>
                  </div>
                </div>
                <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
              </div>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-primary btn-lg btn-block" href="/box/detail/<?php echo $box['box_id'] ?>">ボックスの詳細に戻る</a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <button type="submit" class="btn btn-danger btn-lg btn-block">ボックス情報を保存する</button>
            </span>
            <?php echo $this->Form->hidden('Box.box_id'); ?>
          <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>
