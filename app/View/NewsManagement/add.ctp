    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-cog"></i> ニュース編集</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create('News', ['url' => '/news_management/add', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12 none-title">
                <div class="form-group col-lg-12">
                <label>タイトル</label>
                  <?php echo $this->Form->input('News.title', ['class' => "form-control", 'placeholder'=>'タイトル', 'error' => false]); ?>
                  <?php echo $this->Form->error('News.title', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                <label>日時</label>
                  <?php echo $this->Form->input('News.date', ['class' => "form-control", 'placeholder'=>'日時（yyyy/mm/dd hh:ii）※マイページに表示される日付', 'error' => false]); ?>
                  <?php echo $this->Form->error('News.date', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                <label>本文 （e.g. 段落：&lt;p&gt;&lt;/p&gt;, 改行：&lt;br&gt;）</label>
                  <?php echo $this->Form->textarea('News.detail', ['class' => "form-control", 'rows' => 15, 'error' => false]); ?>
                  <?php echo $this->Form->error('News.detail', null, ['wrap' => 'p']) ?>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/news_management">戻る</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">登録する</button>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
