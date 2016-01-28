  <div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-heart-o"></i> アイテム</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <h2>アイテムの一覧</h2>
              <div class="col-lg-2 col-lg-offset-10">
                <select class="form-control sort-form">
                  <option>並べ替え</option>
                  <option value="">箱NO（降順）</option>
                  <option value="">箱NO（昇順）</option>
                  <option value="">箱タイトル（降順）</option>
                  <option value="">箱タイトル（昇順）</option>
                  <option value="">個品タイトル（降順）</option>
                  <option value="">個品タイトル（昇順）</option>
                  <option value="">ステータス（降順）</option>
                  <option value="">ステータス（昇順）</option>
                  <option value="">オプション（降順）</option>
                  <option value="">オプション（昇順）</option>
                </select>
              </div>
              <div class="col-lg-12">
                <ul class="tile">
                  <!--loop-->
                  <?php foreach ($itemList as $item): ?>
                  <li class="panel panel-default">
                      <a href="/item/detail/<?php echo $item['item_id'] ?>">
                          <img src="<?php echo $item['images_item']['image_url'] ?>" alt="<?php echo $item['item_id'] ?>">
                      </a>
                    <div class="panel-footer">
                      <p class="box-list-caption"><span>アイテム名</span><?php echo $item['item_name']; ?></p>
                      <p class="box-list-caption"><span>アイテムID</span><?php echo $item['item_id']; ?></p>
                    </div>
                  </li>
                  <?php endforeach; ?>
                  <!--loop end-->
                </ul>
              </div>
              <?php echo $this->element('paginator'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
