var AppOutboundCloset =
  {
    item: [],
    display_item: [],
    box: [],
    display_box: [],
    init: function() {
      $.when(AppOutboundCloset.getAllClosetItem(), AppOutboundCloset.getAllClosetBox())
        .then(AppOutboundCloset.renderNoItem)
        .then(AppOutboundCloset.changeSelectDeposit)
        .then(AppOutboundCloset.triggerRedisplay)
        .then(AppOutboundCloset.allSelect)
        .then(AppOutboundCloset.searchTxt)
        .then(AppOutboundCloset.search)
        .then(AppOutboundCloset.checkSelectedItems)
        .then(AppOutboundCloset.submitForm)
        .then(AppOutboundCloset.disableButton)
        .then(AppOutboundCloset.checkActiveButton)
        .then(AppOutboundCloset.clickItem);
    },
    getAllClosetItem: function() {
      var d = new $.Deferred();
      $.ajax({
        type: "GET",
        url: "/outbound/as_get_closet_item",
      })
        .then(
          // 通信成功
          function(jsonResponse){
            AppOutboundCloset.item = JSON.parse(jsonResponse);
            d.resolve();
          },
          // 通信失敗
          function(){
            d.reject();
          }
        );
      return d.promise();
    },
    getAllClosetBox: function() {
      var d = new $.Deferred();
      $.ajax({
        type: "GET",
        url: "/outbound/as_get_closet_box",
      })
        .then(
          // 通信成功
          function(jsonResponse){
            AppOutboundCloset.box = JSON.parse(jsonResponse);
            d.resolve();
          },
          // 通信失敗
          function(){
            d.reject();
          }
        );
      return d.promise();
    },
    renderNoItem: function() {
      if (AppOutboundCloset.item.length == 0) {
          var html = '';
          html += '<p class="form-control-static col-lg-12">ただ今、お預かりしているお品物はございません。<br />';
          html += '梱包キットをお持ちでない方は、弊社指定の専用キットをご購入ください。<br />';
          html += '梱包キットをお持ちの方は、預け入れのお手続きにすすんでください。</p>';
          $('.grid').empty();
          $('.grid').append(html);
          // footerボタンを削除
          $('.nav-fixed').remove();
          return new $.Deferred().reject().promise();
      } else {
          return new $.Deferred().resolve().promise();
      }
    },
    render: function(checked_remain_flg) {
      checked_remain_flg = checked_remain_flg || false;

      var item = [];
      var box = [];
      // 表示エリアをクリアする
      if (checked_remain_flg == false) {
          $('.grid').empty();
      } else {
        if($("input[name='select-deposit']:checked").val() == 'item') {
          $.each($('input[name="item_id[]"]:checked'), function(i, v){
            $.each(AppOutboundCloset.item, function(ii, vv){
              if (vv.item_id == $(v).val()) {
                var exist = false;
                item.push(vv.item_id);
                $.each(AppOutboundCloset.display_item, function(iii, vvv){
                  if(vvv.item_id == $(v).val()) {
                    exist = true;
                  }
                })
                if (exist == false) {
                  AppOutboundCloset.display_item.push(vv);
                }
              }
            })
          })
        } else {
          $.each($('input[name="box_id[]"]:checked'), function(i, v){
            $.each(AppOutboundCloset.box, function(ii, vv){
              if (vv.box_id == $(v).val()) {
                var exist = false;
                box.push(vv.box_id);
                $.each(AppOutboundCloset.display_box, function(iii, vvv){
                  if(vvv.box_id == $(v).val()) {
                    exist = true;
                  }
                })
                if (exist == false) {
                  AppOutboundCloset.display_box.push(vv);
                }
              }
            })
          })
        }
        $('.grid').empty();
      }

      var deposit = $("input[name='select-deposit']:checked").val();
      if (deposit == "item") {
        $.each(AppOutboundCloset.display_item, function(index, value){
          var renderItem = '';
          renderItem += '<li>';
          renderItem += '    <label class="input-check">';
          renderItem += '        <input id="'+value.item_id+'" type="checkbox" class="cb-circle" name="item_id[]" value="'+value.item_id+'"><span class="icon"></span>';
          renderItem += '        <span class="item-img"><img src="'+value.image_first.image_url+'" alt="'+value.item_id+'" class="img-item"></span>';
          renderItem += '    </label>';
          renderItem += '    <div class="item-caption">';
          renderItem += '        <p class="item-name">'+value.item_name+'</p>';
          renderItem += '        <p class="item-id">'+value.item_id+'</p>';
          renderItem += '    </div>';
          renderItem += '</li>';
          $('.grid').append(renderItem);
        });
        if (item.length > 0) {
          $.each(item, function(i, v){
            $("#"+v).prop('checked', true);
          });
        }
      }
      if (deposit == "box") {
        $.each(AppOutboundCloset.display_box, function(index, value){
          var renderItem = '';
          renderItem += '<li>';
          renderItem += '    <label class="input-check">';
          renderItem += '        <input id="'+value.box_id+'" type="checkbox" class="cb-circle" name="box_id[]" value="'+value.box_id+'"><span class="icon"></span>';
          renderItem += '        <span class="item-img"><img src="/images/order/box_cleaning@1x.png" alt="'+value.box_id+'" class="img-item"></span>';
          renderItem += '    </label>';
          renderItem += '    <div class="item-caption">';
          renderItem += '        <p class="item-name">'+value.box_name+'</p>';
          renderItem += '        <p class="item-id">'+value.box_id+'</p>';
          renderItem += '    </div>';
          renderItem += '</li>';
          $('.grid').append(renderItem);
        });
        if (box.length > 0) {
          $.each(box, function(i, v){
            $("#"+v).prop('checked', true);
          });
        }
      }
      AppOutboundCloset.clickItem();

      return new $.Deferred().resolve().promise();
    },
    changeSelectDeposit: function() {
      var selected_item_ids = $("#selected_item_ids").val();
      var selected_box_ids = $("#selected_box_ids").val();

      if (selected_item_ids != '') {
        selected_item_ids = selected_item_ids.split(',');
        $("input[name='select-deposit']:eq(0)").prop('checked', true);
      } else {
        selected_item_ids = [];
      }

      if (selected_box_ids != '') {
        selected_box_ids = selected_box_ids.split(',');
        $("input[name='select-deposit']:eq(1)").prop('checked', true);
      } else {
        selected_box_ids = [];
      }

      return new $.Deferred().resolve().promise();
    },
    checkSelectedItems: function() {
      var selected_item_ids = $("#selected_item_ids").val();
      var selected_box_ids = $("#selected_box_ids").val();

      if (selected_item_ids != '') {
        selected_item_ids = selected_item_ids.split(',');
        $.each(selected_item_ids, function(index, value){
          $("#"+value).prop('checked', true);
        });
      }

      if (selected_box_ids != '') {
        selected_box_ids = selected_box_ids.split(',');
        $.each(selected_box_ids, function(index, value){
          $("#"+value).prop('checked', true);
        });
      }

      AppOutboundCloset.checkActiveButton();
      return new $.Deferred().resolve().promise();
    },
    triggerRedisplay: function() {
      $("input[name='select-deposit']").change(function(){
        // チェックされているアイテムが存在する場合はクリアされる確認
        if ($('input[name="box_id[]"]:checked,input[name="item_id[]"]:checked').length > 0) {
          var txt = ($("input[name='select-deposit']:checked").val() == 'item') ? 'ボックス' : 'アイテム';
          var ret = window.confirm('現在ご選択中の' + txt + 'がクリアされます。よろしいですか？');
          if (ret == true) {
              $('#execute').css('opacity','0.3');
              $('#execute').prop('disabled',true);
              AppOutboundCloset.search($("#search_txt").val());
          } else {
            if ($("input[name='select-deposit']:eq(0)").prop('checked')) {
              $("input[name='select-deposit']:eq(1)").prop('checked', true);
            } else {
              $("input[name='select-deposit']:eq(0)").prop('checked', true);
            }
          }
        } else {
          AppOutboundCloset.search($("#search_txt").val());
        }
      });
      return new $.Deferred().resolve().promise();
    },
    allSelect: function() {
      $("#all_select").click("on", function(){
        if ($("#all_select").prop("checked")) {
          $('input[name="box_id[]"], input[name="item_id[]"]').prop("checked", true);
          $('#execute').css('opacity','1');
          $('#execute').prop('disabled',false);
        } else {
          $('#execute').css('opacity','0.3');
          $('#execute').prop('disabled',true);
          $('input[name="box_id[]"], input[name="item_id[]"]').prop("checked", false);
        }
      });
      return new $.Deferred().resolve().promise();
    },
    searchTxt: function() {
      var stack = [];
      $("#search_txt").on("keyup paste", function(){
        stack.push(1);
        setTimeout(function() {
          stack.pop();
          if (stack.length == 0) {
            AppOutboundCloset.search($("#search_txt").val());
            stack = [];
          }
        }, 1000);
      });
      return new $.Deferred().resolve().promise();
    },
    search: function(txt) {
      // 検索文字列が空の場合は全てを表示する
      if (txt == '' || txt == undefined) {
        AppOutboundCloset.display_item = AppOutboundCloset.item;
        AppOutboundCloset.display_box = AppOutboundCloset.box;
        AppOutboundCloset.render(true);
        return true;
      }

      // item の検索
      var disp_item = [];
      $.each(AppOutboundCloset.item, function(index, value){
        if(value.item_id.indexOf(txt) != -1) {
          disp_item.push(value);
          return true; // continue
        }
        if(value.item_name.indexOf(txt) != -1) {
          disp_item.push(value);
          return true; // continue
        }
        if(value.box_name.indexOf(txt) != -1) {
          disp_item.push(value);
          return true; // continue
        }
        if(value.box_id.indexOf(txt) != -1) {
          disp_item.push(value);
          return true; // continue
        }
      });

      AppOutboundCloset.display_item = disp_item;

      // box の検索
      var disp_box = [];
      $.each(AppOutboundCloset.box, function(index, value){
        if(value.box_name.indexOf(txt) != -1) {
          disp_box.push(value);
          return true; // continue
        }
        if(value.box_id.indexOf(txt) != -1) {
          disp_box.push(value);
          return true; // continue
        }
      });

      AppOutboundCloset.display_box = disp_box;

      AppOutboundCloset.render(true);

      return new $.Deferred().resolve().promise();
    },
    disableButton: function() {
      // ボタンを非活性
      $('#execute').css('opacity','0.3');
      $('#execute').prop('disabled',true);
      return new $.Deferred().resolve().promise();
    },
    checkActiveButton: function() {
      if ($('input[name="box_id[]"]:checked,input[name="item_id[]"]:checked').length > 0) {
        $('#execute').css('opacity','1');
        $('#execute').prop('disabled',false);
      } else {
        $('#execute').css('opacity','0.3');
        $('#execute').prop('disabled',true);
      }
      return new $.Deferred().resolve().promise();
    },
    clickItem: function() {
      $('input[name="box_id[]"],input[name="item_id[]"]').click("on", function(){
        AppOutboundCloset.checkActiveButton();
      });
      return new $.Deferred().resolve().promise();
    },
    submitForm: function() {
      $("#execute").click("on", function(){
        document.form.submit();
      });
      return new $.Deferred().resolve().promise();
    },
  }

/*
 * document ready
 * */
$(function()
  {
    AppOutboundCloset.init();
  });
