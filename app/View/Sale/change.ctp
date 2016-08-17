    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-basket"></i> アイテム販売</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>販売機能設定</h2>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="row">
                <span class="col-sm-6 col-xs-12">
                <a class="btn btn-info btn-xs btn-block" href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/" target="_blank">minikura利用規約</a>
                </span>
              </div>
              <div class="checkbox">
                <label>
                  <input class="agree-before-submit" type="checkbox">
                  minikura利用規約に同意する </label>
              </div>
            </div>
            <?php echo $this->Form->create('Sale', ['url' => ['controller' => 'Sale', 'action' => 'complete']]); ?>
            <span class="col-lg-12 col-md-12 col-xs-12">
              <?php echo $this->Form->hidden('setting', ['value' => 'on']); ?>
              <button type="submit" class="btn btn-danger btn-lg btn-block">販売機能をONにする</a>
            </span>
            <?php echo $this->Form->end(); ?>

            <?php echo $this->Form->create('Sale', ['url' => ['controller' => 'Sale', 'action' => 'complete']]); ?>
             <span class="col-lg-12 col-md-12 col-xs-12">
              <?php echo $this->Form->hidden('setting', ['value' => 'off']); ?>
              <button type="submit" class="btn btn-danger btn-lg btn-block">販売機能をOFFにする</a>
            </span>
            <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>
