  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><i class="fa fa-home"></i> マイページ</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 col-xs-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <h2>メッセージ</h2>
          <div class="col-lg-12">
            <div class="col-lg-12 announcement">
            <?php foreach ($notice_announcements as $data): ?>
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
          <div class="col-lg-12 col-md-12 col-xs-12">
            <a class="btn btn-info btn-md pull-right" href="/announcement/">メッセージ一覧を見る</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 col-xs-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <h2>ニュース</h2>
          <div class="col-lg-12">
            <div class="col-lg-12 announcement">
            <?php if(empty($newsList)) :?>
              <div class=row><p>ただいま表示できるニュースはありません</p></div>
            <?php else:?>
              <?php foreach ($newsList as $data): ?>
                <div class="row list">
                  <div class="col-xs-12 col-md-3 col-lg-3">
                    <?php echo $this->Html->formatYmdKanji($data['disp_date']); ?>
                  </div>
                  <div class="col-xs-12 col-md-8 col-lg-8">
                    <span class="detail"><a href="/news/detail/<?php echo $data['id'];?>"><?php echo h($data['title']); ?></a></span>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif;?>
            </div>
          </div>
          <?php if(!empty($newsList)) :?>
            <div class="col-lg-12 col-md-12 col-xs-12">
              <a class="btn btn-info btn-md pull-right" href="/news/">ニュース一覧を見る</a>
            </div>
          <?php endif;?>
        </div>
      </div>
    </div>
    <?php if (!$customer->isEntry()) : ?>
    <div class="col-lg-12 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-body">
			<div class="row">
			  <div class="col-lg-12">
				<h2>契約情報一覧</h2>
				<div class="col-lg-12 col-xs-12 agreement">
				  <div class="form-group col-lg-12">
					<?php 
					if($customer->isSneaker()):
					  $productCdList = [PRODUCT_CD_SNEAKERS];
					else:
					  $productCdList = [PRODUCT_CD_MONO, PRODUCT_CD_HAKO, PRODUCT_CD_CARGO_JIBUN, PRODUCT_CD_CARGO_HITOMAKASE, PRODUCT_CD_CLEANING_PACK, PRODUCT_CD_SHOES_PACK];
					endif;?>
					<?php foreach($productCdList as $productCd) : ?>
					<div class="row list">
					  <div class="col-xs-12 col-md-10 col-lg-10">
						<?php echo PRODUCT_NAME[$productCd]; ?>
					  </div>
					  <div class="col-xs-12 col-md-2 col-lg-2">
						<?php echo Hash::get($product_summary, $productCd, 0); ?>箱
					  </div>
					</div>
					<?php endforeach; ?>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>	
    <?php endif; ?>
  </div>
