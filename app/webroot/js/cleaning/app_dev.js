$(function () {
  // 起動時の初期処理
  AppSelection.initialize();
  AppCleaning.a();
  AppCleaning.b();
  AppCleaning.c();
});

var AppCleaning = {
  a : function() {
    // アイテムのチェックボックスに変化が起きた場合の処理
    $(document).on("change", ".item-select input[type=checkbox]", function(){
      // チェックされている場合はリストから削除する
      if ($(this).prop("checked")) {
        AppSelection.addList($(this).data("itemid"), $(this).data("price"), $(this).val());
      } else {
        AppSelection.delList($(this).data("itemid"));
      }
      AppSelection.calcTotal();
      AppSelection.check();
    });
  },
  b : function() {
    // クリアーボタンが押された時の処理
    $("#ClearSelected").click(function() {
      // すべてのクッキーを削除する
      docCookies.removeItem("mn_cleaning_list");
      $("#itemlist .item .item-select input[type=checkbox]:checked").prop("checked", false);
      AppSelection.calcTotal();
      AppSelection.check();
    });
  },
  c : function() {
    // 確認ボタンが押された場合の処理
    $('.item_confirm').click(function() { 
      if ($("#itemlist .item .item-select input[type=checkbox]:checked").length == 0) {
        if (!$("#flashMessage").length) {
          var _msg = $('<p id="flashMessage" class="error-message message" style="display:none;">アイテムを選択してください。</p>');
            $(".page-header").before(_msg);
            _msg.slideDown("fast",function(){
                scrollTo($("#flashMessage"), -200);
            });
        } else {
          scrollTo($("#flashMessage"), -200);
        }
      } else {
        $("#itemlist").submit();
      }
    });
  },
}

var AppSelection = {
  initialize : function() {
    // initialize : 起動時の初期処理
    // cookieから選択されているリストを取得する
    var list = docCookies.getItem("mn_cleaning_list");
    
    // cookieに選択リストがある場合
    // SelectedID(GET)からのパラメタ―がある場合
    if (list || $("#selected_id").val()) {
      // リストは「コンマ」区切りでItemID保管されているので分解
      if (list) {
        var listData = JSON.parse(list);
      } else {
        var listData = new Array;
      }
      var listSelected = new Array;
      
      for (i=0; i<listData.length; i++) {
        // チェックボックスを処理のため、リストを作成をする
        listSelected.push(listData[i].item_id);
      }

     // Itemlist内のアイテムのループ処理
      $("#itemlist .item .item-select input[type=checkbox]").each(function() {
        // 要素からitemIdを取得
        itemId = $(this).data("itemid");
        
        if ($("#selected_id").val() === itemId) {
            $(this).prop("checked", true);
            AppSelection.addList(itemId, $(this).data("price"), $(this).val());
        } else {
          // 選択リストにItemIDが存在する場合はチェックを入れる
          if ($.inArray(itemId, listSelected) !== -1) {
            $(this).prop("checked", true);
          }
        }
      });
      AppSelection.calcTotal();
    }
    
    // リストデータの更新(金額の計算、数の表示）
    AppSelection.check();

    var current_page = $("#current_page").val();
    path=new Array();
    path.push(location.href + "?page=");
    path.push("");
    
    // Infinitescroll
    $(".grid ul").infinitescroll({
      dataType         : "html",
      navSelector    : ".pagination",
      nextSelector   : ".pagination .next",
      itemSelector   : "#itemlist ul li",
      path                : path,
      state: {
        currPage          : current_page,
      }, 
      loading           : { 
        finishedMsg     : "",
        msgText           : "",
      },
    },function(){
      // 追加されて要素にremodal機能を追加
      $('.remodal').remodal();
    });
  },
  addList : function(_id, _price, _data) {
    // addList : Cookieにリストを追加する
    // cookieから選択されているリストを取得する
    var list = docCookies.getItem("mn_cleaning_list");
    
    // 既存のリストを整理する
    if (list) {
      var listData = JSON.parse(list);
    } else {
      var listData = new Array;
    }
    var flg_add = true;
    
    for (i=0; i<listData.length; i++) {
      // チェックボックスを処理のため、リストを作成をする
      if (_id == listData[i].item_id) {
        flg_add = false;
        break;
      }
    }
    
    if (flg_add) {
      var new_value = {
        item_id       : _id,
        price          : _price,
        data           : _data
      };
      listData.push(new_value);

      // Cookieに保存
      docCookies.setItem("mn_cleaning_list", JSON.stringify(listData));
    }
  },
  delList : function(_id) {
    // delList : Cookieにリストから削除する
    // cookieから選択されているリストを取得する
    var list = docCookies.getItem("mn_cleaning_list");
    
    // 既存のリストを整理する
    if ( list ) {
      var listData = JSON.parse(list);
      var new_list = new Array;
      for (i=0; i<listData.length; i++) {
        // チェックボックスを処理のため、リストを作成をする
        if (_id !== listData[i].item_id) {
          // リスト配列に追加する
          new_list.push(listData[i]);
        }
      }
      // Cookieに保存
      docCookies.setItem("mn_cleaning_list", JSON.stringify(new_list));
    }
  },
  calcTotal : function() {
    // calcTotal : クッキーから総額をチェックする
    // 使う変数の定義
    var totalprice = 0;
    var totalselected = 0;

    // cookieから選択されているリストを取得する
    var list = docCookies.getItem("mn_cleaning_list");
    
    // cookieに選択リストがある場合
    if (list) {
      // 保管されているデータを変換
      var listData = JSON.parse(list);
      var listSelected = new Array;
      
      for (i=0; i<listData.length; i++) {
        // チェックボックスを処理のため、リストを作成をする
        listSelected.push(listData[i].item_id);
        
        totalprice += parseInt(listData[i].price);
        totalselected++;
      }
    } else {
        totalprice = 0;
        totalselected = 0;
    }

    // 金額合計要素に合計金額を表示
    $(".block_selected_price").text(totalprice.toLocaleString());
    // 選択合計要素に総計を表示
    $(".block_selected_item").text(totalselected);
  },
  check : function() {
    // チェックされているアイテムがなければボタンを有効/無効にする
    // cookieから選択されているリストを取得する
    var list = docCookies.getItem("mn_cleaning_list");
    
    if ( list ) {
      var listData = JSON.parse(list);
      if (listData.length < 1) {
        $(".item_confirm").addClass("disabled");
      } else {
        $("#flashMessage").slideUp("fast");
        $("#flashMessage").remove();
        $(".item_confirm").removeClass("disabled");
      }
    } else {
        $(".item_confirm").addClass("disabled");
    }
  },
};
