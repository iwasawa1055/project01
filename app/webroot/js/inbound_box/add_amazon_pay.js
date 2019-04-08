var AppInboundBoxAdd =
{
  new_box: [],
  display_new_box: [],
  old_box: [],
  display_old_box: [],
  init: function() {
    $.when(AppInboundBoxAdd.getAllNewBox(), AppInboundBoxAdd.getAllOldBox())
      .then(AppInboundBoxAdd.render);
    // 初期表示
    if (typeof $("#dev-selected-box_type").val() !== 'undefined') {
      var box_type = $("#dev-selected-box_type").val();
      if (box_type == "new") {
        $('[name="data[Inbound][box_type]"]:eq(0)').prop('checked', true);
        $('#dev-new-box').fadeIn(400, 'easeInOutExpo');
        $('#dev-old-box').fadeOut(400, 'easeInOutExpo');
        $("#dev-self-delivery").show();
        $("#dev-input-box-type-new").show();
      } else {
        $('[name="data[Inbound][box_type]"]:eq(1)').prop('checked', true);
        $("input[name='data[Inbound][delivery_carrier]']:eq(0)").prop('checked', true);
        $("#dev-self-delivery").hide();
        $("#dev-input-box-type-new").show();
        $('#dev-new-box').fadeOut(400, 'easeInOutExpo');
        $('#dev-old-box').fadeIn(400, 'easeInOutExpo');
      }
    } else {
        $('[name="data[Inbound][box_type]"]:eq(0)').prop('checked', true);
        $('#dev-new-box').fadeIn(400, 'easeInOutExpo');
        $('#dev-old-box').fadeOut(400, 'easeInOutExpo');
        $("#dev-self-delivery").show();
        $("#dev-input-box-type-new").show();
    }

    $('[name="data[Inbound][box_type]"]').change(function() {
      if ($('.dev-box-check:checked').length > 0) {
        var ret = window.confirm('現在ご選択中のボックスがクリアされます。よろしいですか？');
        if (ret == true) {
          $('.dev-box-check:checked').prop('checked', false);
        } else {
          if ($('[name="data[Inbound][box_type]"]:eq(0)').prop('checked')) {
            $('[name="data[Inbound][box_type]"]:eq(1)').prop('checked', true);
          } else {
            $('[name="data[Inbound][box_type]"]:eq(0)').prop('checked', true);
          }
        }
      }

      if ($(this).val() === 'new') {
        $('#dev-new-box').fadeIn(400, 'easeInOutExpo');
        $('#dev-old-box').fadeOut(400, 'easeInOutExpo');
        $("#dev-self-delivery").show();
        $("#dev-input-box-type-new").show();
      } else if ($(this).val() === 'old') {
        $("input[name='data[Inbound][delivery_carrier]']:eq(0)").prop('checked', true);
        $("#dev-self-delivery").hide();
        $("#dev-input-box-type-new").show();
        $('#dev-new-box').fadeOut(400, 'easeInOutExpo');
        $('#dev-old-box').fadeIn(400, 'easeInOutExpo');
      }
    });

    $('[name="data[Inbound][delivery_carrier]"]').change(function() {
      if ($(this).val() === '7') {
        $("#dev-input-box-type-new").hide();
      } else {
        $("#dev-input-box-type-new").show();
      }
    });

    $("#execute").click("on", function(){
        var add_billing  = $('<input type="hidden" name="amazon_order_reference_id">');
        add_billing.val(AppAmazonPayWallet.AmazonOrderReferenceId);
        $("form").append(add_billing);
        document.form.submit();
    });
  },
  getAllNewBox: function() {
    var d = new $.Deferred();
    $.ajax({
      type: "GET",
      url: "/inbound/box/as_get_new_box",
    })
      .then(
        // 通信成功
        function(jsonResponse){
          AppInboundBoxAdd.new_box = JSON.parse(jsonResponse);
          d.resolve();
        },
        // 通信失敗
        function(){
          d.reject();
        }
      );
    return d.promise();
  },
  getAllOldBox: function() {
    var d = new $.Deferred();
    $.ajax({
      type: "GET",
      url: "/inbound/box/as_get_old_box",
    })
      .then(
        // 通信成功
        function(jsonResponse){
          AppInboundBoxAdd.old_box = JSON.parse(jsonResponse);
          d.resolve();
        },
        // 通信失敗
        function(){
          d.reject();
        }
      );
    return d.promise();
  },
  render: function() {
    $.each(AppInboundBoxAdd.new_box, function(index, value){
      var renderNewBox = AppInboundBoxAdd.createHtml(value);
      $('#dev-new-box-grid').append(renderNewBox);
    });
    $.each(AppInboundBoxAdd.old_box, function(index, value){
      var renderOldBox = AppInboundBoxAdd.createHtml(value);
      $('#dev-old-box-grid').append(renderOldBox);
    });

    // error
    if (typeof $("#dev-box-list-errors").val() !== 'undefined') {
      var boxListErrors = JSON.parse($("#dev-box-list-errors").val());
      $.each(boxListErrors, function(index, value){
        $.each(value, function(i1, v1){
          $.each(v1, function(i2, v2){
            $('[name="data[Inbound][box_list]['+index+']['+i1+']"').after('<p class="valid-il">'+v2+'</p>');
          });
        });
      });
    }
    // selected
    if (typeof $("#dev-box-list-selected").val() !== 'undefined') {
      var boxListSelected = JSON.parse($("#dev-box-list-selected").val());
      $.each(boxListSelected, function(index, value){
        $('[name="data[Inbound][box_list]['+index+'][title]"').val(value.title);
        $('[name="data[Inbound][box_list]['+index+'][checkbox]"').prop('checked', true);
      });
    }
    return new $.Deferred().resolve().promise();
  },
  createHtml: function(value) {
    var html = '';
    html += '<li>';
    html += '    <label class="input-check">';
    html += '        <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][box_id]" value="'+value.box_id+'" id="InboundBoxList'+value.box_id+'BoxId">';
    html += '        <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][product_cd]" value="'+value.product_cd+'" id="InboundBoxList'+value.box_id+'ProductCd">';
    html += '        <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][product_name]" value="'+value.product_name+'" id="InboundBoxList'+value.box_id+'ProductName">';
    html += '        <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][kit_cd]" value="'+value.kit_cd+'" id="InboundBoxList'+value.box_id+'KitCd">';
    html += '        <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][checkbox]" class="cb-circle dev-box-check" value="0">';
    html += '        <input type="checkbox" name="data[Inbound][box_list]['+value.box_id+'][checkbox]" class="cb-circle dev-box-check" value="1"><span class="icon"></span>';
    html += '        <span class="item-img"><img src="'+AppInboundBoxAdd.getProductImage(value.kit_cd)+'" alt="'+value.kit_name+'" class="img-item"></span>';
    html += '    </label>';
    html += '    <div class="box-info">';
    html += '        <p class="box-id">'+value.box_id+'</p>';
    html += '        <p class="box-type">'+value.kit_name+'</p>';
    html += '        <input type="text" name="data[Inbound][box_list]['+value.box_id+'][title]" placeholder="ボックス名を記入してください" class="box-input-name">';

    if (value.kit_cd == '66' || value.kit_cd == '67' || value.kit_cd == '82') {
      html += '        <label class="input-check">';
      html += '            <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][wrapping_type]" class="cb-circle dev-box-check" value="0">';
      html += '            <input type="checkbox" name="data[Inbound][box_list]['+value.box_id+'][wrapping_type]" class="cb-square dev-box-check" value="1">';
      html += '            <span class="icon"></span>';
      html += '            <span class="label-txt">外装を除いて撮影</span>';
      html += '        </label>';
    }

    html += '    </div>';
    html += '</li>';
    return html;
  },
  getProductImage: function(kit_cd) {
    switch (kit_cd){
      // HAKO レギュラー
      case '64':
        return '/images/hako-regular.png';
      // HAKO アパレル
      case '65':
        return '/images/hako-apparel.png';
      // HAKO ブック
      case '81':
        return '/images/hako-book.png';

      // MONO レギュラー
      case '66':
        return '/images/mono-regular.png';
      // MONO アパレル
      case '67':
        return '/images/mono-apparel.png';
      // MONO ブック
      case '82':
        return '/images/mono-regular.png';

      // クリーニングパック
      case '75':
        return '/images/cleaning.png';

      // Library
      case '214':
      case '215':
        return '/images/library.png';

      // closet
      case '216':
        return '/images/cleaning.png';
    }
  },
}

var AppAmazonPayWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonOrderReferenceId: '',
    buyerBillingAgreementConsentStatus: false,
    AmazonWidgetReadyFlag: false,

    a: function () {
        // amazon Widget Ready
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppAmazonPayWallet.ClientId);
            AppAmazonPayWallet.b();
        };
    },
    b: function () {
        // アドレスWidgetを表示
        new OffAmazonPayments.Widgets.AddressBook({
            sellerId: AppAmazonPayWallet.SELLER_ID,
            // Widgets起動状態
            onReady: function(billingAgreement) {
                AppAmazonPayWallet.AmazonWidgetReadyFlag = true;
                AppAmazonPayWallet.AmazonOrderReferenceId = billingAgreement.getAmazonOrderReferenceId();
            },
            onAddressSelect: function () {
                AppAmazonUserNameDevide.a(AppAmazonPayWallet.AmazonOrderReferenceId);
            },
            design: {
                designMode: 'responsive'
            },
            onError: function (error) {
                JsError.a("/inbound_box/add_amazon_pay", error.getErrorCode() + error.getErrorMessage());
                if(error.getErrorCode() == 'BuyerSessionExpired') {
                    amazon.Login.logout();
                    location.href = '/login/logout';
                }
            }
        }).bind("addressBookWidgetDiv");
    }
}

var AppAmazonUserNameDevide =
{
  a: function(amazon_order_reference_id)
  {
    $.post('/InboundBox/getAmazonUserInfoDetail', {
              amazon_order_reference_id: amazon_order_reference_id
          },

          function (data) {
            AppAmazonUserNameDevide.removeNameFormValue();

            if (data.status) {
                AppAmazonUserNameDevide.setNameFormValue(data.name.lastname, data.name.firstname);
            } else {
              //AmazonPayからの名前データ取得失敗時は入力フォーム表示
                var elem_deca = $('#InboundDeliveryCarrier');
                if (elem_deca.val().indexOf(DELIVERY_ID_PICKUP) !== -1) {
                    AppAmazonUserNameDevide.showNameForm();
                }
            }
          }
          ,
          'json'
      );
  },
  showNameForm: function()
  {
    $(".name-form-group").css('display', 'block');
  },
  hideNameForm: function()
  {
    $(".name-form-group").css('display', 'none');
  },
  setNameFormValue: function(lastname, firstname)
  {
    $(".lastname").val(lastname);
    $(".firstname").val(firstname);
    AppAmazonUserNameDevide.hideNameForm();
  },
  removeNameFormValue: function()
  {
    $(".lastname").val('');
    $(".firstname").val('');
  },
}

/*
 * document ready
 * */
$(function()
{
  AppInboundBoxAdd.init();

  //集荷日を選択時に集荷時間をセットする
  PickupYamato.changeSelectPickup();

  // 集荷日と集荷時間取得
  PickupYamato.getDateTime();
});

// Amazonpay Callback
AppAmazonPayWallet.a();

// モーダルでエラーが発生するので打ち消し
function scrollTo(_target,_correction,_speed) {}
