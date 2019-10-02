    <?php
      $this->Html->script('outbound/closet', ['block' => 'scriptMinikura']);
    ?>

    <form method="POST" action="/outbound/closet_select_item" name="form">
      <div id="page-wrapper" class="wrapper outbound">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> minikura Closet</h1>
        <ul class="pagenation">
          <li class="on"><span class="number">1</span><span class="txt">アイテム<br>選択</span>
          </li>
          <li><span class="number">2</span><span class="txt">配送情報<br>入力</span>
          </li>
          <li><span class="number">3</span><span class="txt">確認</span>
          </li>
          <li><span class="number">4</span><span class="txt">完了</span>
          </li>
        </ul>
          <?php if (isset($complete_error)) : ?>
            <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i><?php echo $this->Flash->render('complete_error');?></div>
          <?php endif; ?>
          <?php if (isset($no_select_item_error)) : ?>
            <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> アイテムが選択されていません</div>
          <?php endif; ?>
          <?php if (isset($over_select_item_error)) : ?>
            <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> 選択したアイテムの合計が300点を超えているので、分割をして出庫をしてください。</div>
          <?php endif; ?>
        <ul class="setting-switcher">
          <li>
            <label class="setting-switch">
              <input type="radio" class="ss" name="select-deposit" value="item" checked="">
              <span class="btn-ss"><span class="icon"></span>アイテムから選択</span>
            </label>
          </li>
          <li>
            <label class="setting-switch">
              <input type="radio" class="ss" name="select-deposit" value="box">
              <span class="btn-ss"><span class="icon"></span>ボックスごと取り出す</span>
            </label>
          </li>
        </ul>
        <ul class="item-search">
          <li>
            <input type="search" placeholder="" class="search" id="search_txt">
          </li>
          <li>
            <label class="input-check">
              <input type="checkbox" class="cb-circle" id="all_select">
              <span class="icon"></span>
              <span class="label-txt">すべて選択</span>
            </label>
          </li>
        </ul>
        <div class="item-content">
          <!--<ul class="grid-view grid-library">
          </ul>-->
          <ul class="grid grid-lg">
          </ul>
        </div>

        <input type="hidden" id="selected_item_ids" value="<?php echo $item_id; ?>">
        <input type="hidden" id="selected_box_ids" value="<?php echo $box_id; ?>">
      </div>
    </form>
    <div class="nav-fixed">
      <ul>
        <li><button class="btn-red" id="execute">配送先入力へ</button></li>
      </ul>
    </div>
