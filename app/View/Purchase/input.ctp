  <div class="container">
    <div class="row">
      <div>
        <h2>購入入力</h2>
      </div>
    </div>
    
    <div class="row">
      <div class="step">
        <div>
          <img src="/images/xxx_xxxx.jpg" alt="" />
        </div>
        <div class="caption">
          <h3>商品名商品名商品名商品名商品名商品名商品名商品名商品名商品名</h3>
          <p>商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明</p>
          <p>000,000,000,000,000円</p>
        </div>
      </div>
    </div>
    <div class="row">


      <?php echo $this->Form->create('Purchase', ['url' => '/purchase/'. '9999' . '/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
      <div class="form-group col-lg-12">
        <label>カード情報</label>
        <select name="data[OrderKit][card_seq]" class="form-control" id="OrderKitCardSeq">
          <option value="0">*************111　TEST TEST</option>
        </select>
      </div>
      <div class="form-group col-lg-12">
        <label>セキュリティコード</label>
        <input name="data[OrderKit][security_cd]" class="form-control" placeholder="セキュリティコードを入力してください" maxlength="4" type="text" id="OrderKitSecurityCd"/>
      </div>
      <div class="form-group col-lg-12">
        <label>お届け先</label>
        <select name="data[OrderKit][address_id]" class="form-control select-add-address" id="OrderKitAddressId">
          <option value="">選択してください</option>
        </select>
      </div>
      <div class="form-group col-lg-12">
        <label>お届け希望日時</label>
        <select name="data[OrderKit][datetime_cd]" class="form-control" id="OrderKitDatetimeCd">
        </select>
      </div>

      <div class="text-center btn-buy">
        <button type="submit" class="btn btn-danger btn-lg btn-block">購入内容を確認する</button>
      </div>
      <?php echo $this->Form->end(); ?>


    </div>
  </div>
