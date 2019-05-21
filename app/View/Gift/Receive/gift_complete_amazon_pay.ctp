  <div id="page-wrapper" class="l-detail-gift wrapper">
    <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ギフトをもらう</h1>
    <ul class="pagenation">
      <li><span class="number">1</span><span class="txt">コード<br>入力</span>
      </li>
      <li><span class="number">2</span><span class="txt">確認</span>
      </li>
      <li class="on"><span class="number">3</span><span class="txt">完了</span>
      </li>
    </ul>
    <p class="page-caption">以下の商品の登録が完了しました。<br>
      「預け入れ」メニューから「ボックス預け入れ」に進み、商品が登録されていることをご確認ください。</p>
    <ul class="items">
      <li id="cleaning" class="item">
        <div class="l-image-lineup">
          <picture>
            <img src="/images/order/photo-cleaning@1x.jpg" srcset="/images/order/photo-cleaning@1x.jpg 1x, /images/order/photo-cleaning@2x.jpg 2x" alt="クリーニングパック">
          </picture>
        </div>
        <div class="l-title-lineup">
          <h3 class="title-item">衣類保管5点まで無料<span>クリーニングパック</span></h3>
          <p class="text-description">6ヶ月保管+クリーニング料セット</p>
        </div>
        <div class="l-action-lineup">
          <p class="text-size">W40cm×H40cm×D40cm</p>
          <p class="text-caption">そのまま宅配便で送れる40cm四方の大容量なチャック付き不織布専用バッグです。</p>
          <p class="text-note">ギフトでは5着までが無料、それ以降の衣類には別途保管料がつきます。</p>
        </div>
      </li>
    </ul>
  </div>
  <div class="nav-fixed">
    <ul>
      <li>
        <a class="btn-red" href="/inbound/box/add">ボックス預け入れへ</a>
      </li>
    </ul>
  </div>

  <input type="hidden" value="<?php if (!empty($card_data)): ?>1<?php else: ?>0<?php endif; ?>" id="is_update">
