  <?php $this->Html->css('/css/style.css', ['block' => 'css']); ?>

    <h1 class="page-header"><i class="fa fa-home"></i> マイページ</h1>

<?php echo $this->element('service_description'); ?>

    <ul class="l-banner">
      <li class="l-banner-dtl">
        <a href="/news/detail/414">
          <picture>
            <source media="(min-width: 768px)" srcset="/images/free-box-pc@1x.png 1x, /images/free-box-pc@2x.png 2x">
            <source media="(min-width: 1px)" srcset="/images/free-box-sp@1x.png 1x, /images/free-box-sp@2x.png 2x">
            <img src="/images/free-box-pc@1x.png" alt="ボックス代金が無料になりました 詳しくはこちら">
          </picture>
        </a>
      </li>
      <li class="l-banner-dtl">
        <a href="/inbound/box/add">
          <picture>
            <source media="(min-width: 768px)" srcset="/images/collected-day-pc@1x.png 1x, /images/collected-day-pc@2x.png 2x">
            <source media="(min-width: 1px)" srcset="/images/collected-day-sp@1x.png 1x, /images/collected-day-sp@2x.png 2x">
            <img src="/images/collected-day-pc@1x.png" alt="13時までのお預け入れ申込で当日にお荷物の受取に伺います">
          </picture>
        </a>
      </li>
    </ul>
    <ul id="top-notice" class="l-info">
      <li class="l-info-blk left">
        <h2 class="ttl-info">メッセージ</h2>
        <ul class="l-info-lst">
          <?php foreach ($notice_announcements as $data): ?>
          <?php $url = '/announcement/detail/' . $data['announcement_id']; ?>
          <li>
            <a class="l-info-dtl <?php if ($data['read']): ?>read<?php else: ?>unread<?php endif; ?>" href="<?php echo $url; ?>">
              <span class="l-icon"></span>
              <span class="txt-content"><?php echo h($data['title']); ?></span>
              <span class="txt-data"><?php echo $this->Html->formatYmdKanji($data['date']); ?></span>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
        <a class="btn" href="/announcement/">メッセージ一覧を見る</a>
      </li>
      <li class="l-info-blk right">
        <h2 class="ttl-info">ニュース</h2>
        <ul class="l-info-lst">
          <?php if(empty($newsList)) :?>
          <div class=row><p>ただいま表示できるニュースはありません</p></div>
          <?php else:?>
          <?php foreach ($newsList as $data): ?>
          <li>
            <a class="l-info-dtl" href="/news/detail/<?php echo $data['id'];?>">
              <span class="txt-content"><?php echo h($data['title']); ?></span>
              <span class="txt-data"><?php echo $this->Html->formatYmdKanji($data['disp_date']); ?></span>
            </a>
          </li>
          <?php endforeach; ?>
          <?php endif;?>
        </ul>
        <a class="btn" href="/news/">ニュース一覧を見る</a>
      </li>
    </ul>
    <?php if (!$customer->isEntry()) : ?>
    <div class="l-plan">
      <h2 class="ttl-info">お申し込みのプラン</h2>
      <?php if (empty(!$kit_cd_summary)): ?>
      <ul class="grid grid-md">
        <?php foreach ($kit_cd_summary as $kit_cd => $count): ?>
        <li>
          <div class="l-img-plan">
            <img src="<?php echo KIT_IMAGE[$kit_cd]; ?>" alt="<?php echo KIT_NAME[$kit_cd]; ?>" class="img-plan">
          </div>
          <p class="txt-plan-name"><?php echo KIT_NAME[$kit_cd]; ?></p>
          <p class="txt-plan-num">個数<span class="val"><?php echo $count; ?></span></p>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
      <p>
        現在お申し込みいただいているプランはありません。お申し込みは<a class="red-link" href="/order/add">こちら</a>
      </p>
      <?php endif; ?>
    </div>
    <?php endif; ?>
