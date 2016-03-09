    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-search"></i> 検索結果</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-xs-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <h2>お知らせの検索結果</h2>
            <div class="col-lg-12">
              <div class="col-lg-12 announcement">
                  <?php foreach ($announcementList as $data): ?>
                    <?php $url = '/announcement/detail/' . $data['announcement_id']; ?>
                      <div class="row list">
                        <div class="col-xs-12 col-md-3 col-lg-3">
                            <?php echo $this->Html->formatYmdKanji($data['date']); ?>
                        </div>
                        <div class="col-xs-12 col-md-8 col-lg-8">
                          <span class="detail"><a href="<?php echo $url; ?>"><?php echo h($data['title']); ?></a></span>
                        </div>
                        <div class="col-xs-12 col-md-1 col-lg-1">
                        <?php if ($data['read']): ?>
                          <a class="btn btn-success btn-xs" href="<?php echo $url; ?>">既読</a>
                        <?php else: ?>
                          <a class="btn btn-danger btn-xs" href="<?php echo $url; ?>">未読</a>
                        <?php endif; ?>
                        </div>
                      </div>
                  <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12 col-xs-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <h2>アイテムの検索結果</h2>
            <div class="col-lg-12 announcement">
              <ul class="tile">
                  <?php foreach ($itemList as $item): ?>
                      <?php $url = '/item/detail/' . $item['item_id']; ?>
                      <!--loop-->
                      <li class="panel panel-default">
                        <?php echo $this->element('List/item_icon_body', ['item' => $item]); ?>
                        <?php echo $this->element('List/item_icon_footer', ['item' => $item]); ?>
                      </li>
                      <!--loop end-->
                  <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12 col-xs-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <h2>ボックスの検索結果</h2>
            <div class="row box-list">
                <?php foreach ($boxList as $box): ?>
                  <?php $url = '/box/detail/' . $box['box_id']; ?>
                  <!--loop-->
                  <div class="col-lg-12">
                    <div class="panel panel-default">
                      <?php echo $this->element('List/box_body', ['box' => $box]); ?>
                      <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
                    </div>
                  </div>
                  <!--loop end-->
                <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
