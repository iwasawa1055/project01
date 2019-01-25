var AppOutboundLibrary =
  {
    item: [],
    display_item: [],
    box: [],
    display_box: [],
    init: function() {
      $.when(AppOutboundLibrary.getAllLibraryItem(), AppOutboundLibrary.getAllLibraryBox())
        .then(AppOutboundLibrary.changeSelectDeposit)
        .then(AppOutboundLibrary.triggerRedisplay)
        .then(AppOutboundLibrary.allSelect)
        .then(AppOutboundLibrary.searchTxt)
        .then(AppOutboundLibrary.search)
        .then(AppOutboundLibrary.checkSelectedItems)
        .then(AppOutboundLibrary.submitForm);
    },
    getAllLibraryItem: function() {
      var d = new $.Deferred();
      $.ajax({
        type: "GET",
        url: "/outbound/as_get_library_item",
      })
        .then(
          // 通信成功
          function(jsonResponse){
            AppOutboundLibrary.item = JSON.parse(jsonResponse);
            d.resolve();
          },
          // 通信失敗
          function(){
            d.reject();
          }
        );
      return d.promise();
    },
    getAllLibraryBox: function() {
      var d = new $.Deferred();
      $.ajax({
        type: "GET",
        url: "/outbound/as_get_library_box",
      })
        .then(
          // 通信成功
          function(jsonResponse){
            AppOutboundLibrary.box = JSON.parse(jsonResponse);
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
      // 表示エリアをクリアする
      $('.grid').empty();

      var deposit = $("input[name='select-deposit']:checked").val();
      if (deposit == "item") {
        $.each(AppOutboundLibrary.display_item, function(index, value){
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
      }
      if (deposit == "box") {
        $.each(AppOutboundLibrary.display_box, function(index, value){
          var renderItem = '';
          renderItem += '<li>';
          renderItem += '    <label class="input-check">';
          renderItem += '        <input id="'+value.box_id+'" type="checkbox" class="cb-circle" name="box_id[]" value="'+value.box_id+'"><span class="icon"></span>';
          renderItem += '        <span class="item-img"><img src="/images/order/box_library@1x.png" alt="'+value.box_id+'" class="img-item"></span>';
          renderItem += '    </label>';
          renderItem += '    <div class="item-caption">';
          renderItem += '        <p class="item-name">'+value.box_name+'</p>';
          renderItem += '        <p class="item-id">'+value.box_id+'</p>';
          renderItem += '    </div>';
          renderItem += '</li>';
          $('.grid').append(renderItem);
        });
      }
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

      return new $.Deferred().resolve().promise();
    },
    triggerRedisplay: function() {
      $("input[name='select-deposit']").click("on", function(){
        AppOutboundLibrary.search($("#search_txt").val());
      });
      return new $.Deferred().resolve().promise();
    },
    allSelect: function() {
      $("#all_select").click("on", function(){
        if ($("#all_select").prop("checked")) {
          $('input[name="box_id[]"], input[name="item_id[]"]').prop("checked", true);
        } else {
          $('input[name="box_id[]"], input[name="item_id[]"]').prop("checked", false);
        }
      });
      return new $.Deferred().resolve().promise();
    },
    searchTxt: function() {
      $("#search_txt").keyup("on", function(){
        AppOutboundLibrary.search($("#search_txt").val());
      });
      $("#search_txt").keypress("on", function(){
        AppOutboundLibrary.search($("#search_txt").val());
      });
      return new $.Deferred().resolve().promise();
    },
    search: function(txt) {
      // 検索文字列が空の場合は全てを表示する
      if (txt == '' || txt == undefined) {
        AppOutboundLibrary.display_item = AppOutboundLibrary.item;
        AppOutboundLibrary.display_box = AppOutboundLibrary.box;
        AppOutboundLibrary.render();
        return true;
      }

      // item の検索
      var disp_item = [];
      $.each(AppOutboundLibrary.item, function(index, value){
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

      AppOutboundLibrary.display_item = disp_item;

      // box の検索
      var disp_box = [];
      $.each(AppOutboundLibrary.box, function(index, value){
        if(value.box_name.indexOf(txt) != -1) {
          disp_box.push(value);
          return true; // continue
        }
        if(value.box_id.indexOf(txt) != -1) {
          disp_box.push(value);
          return true; // continue
        }
      });
      AppOutboundLibrary.display_box = disp_box;

      AppOutboundLibrary.render();

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
    AppOutboundLibrary.init();
  });
