$(function () {
  // 起動時の初期処理
  AppSelection.initialize();
  AppCleaning.a();
  AppCleaning.b();
  AppCleaning.c();
  AppCleaning.d();
});

var AppCleaning = {
  a : function() {
    // アイテムのチェックボックスに変化が起きた場合の処理
    $(document).on("change", ".item-select input[type=checkbox]", function(){
      AppSelection.check();
      AppSelection.updateList();
    });
  },
  b : function() {
    // クリアーボタンが押された時の処理
    $("#ClearSelected").click(function() {
      $("#itemlist .item .item-select input[type=checkbox]:checked").prop("checked", false);
      AppSelection.updateList();
    });
  },
  c : function() {
    // 確認ボタンが押された場合の処理
    $('.item_confirm').click(function() { 
      if ($("#itemlist .item .item-select input[type=checkbox]:checked").length == 0) {
        if (!$("#flashMessage").length ) {
          var _msg = $('<p id="flashMessage" class="error-message message" style="display:none;">アイテムを選択してください。</p>');
            $(".page-header").before(_msg);
            _msg.slideDown("fast",function(){
                scrollTo($("#flashMessage"),-200);
            });
        }
      } else {
        $("#itemlist").submit();
      }
    });
  },
  d : function() {
    $("#item-search").submit(function() {
        // Cookieにリセットフラグを保存
        docCookies.setItem("mn_cleaning_reset", "1");
    });
  }
}

var AppSelection = {
  initialize : function() {
    // initialize : 起動時の初期処理
    // cookieから選択されているリストを取得する
    var list = docCookies.getItem("mn_cleaning_list");
    
    // cookieに選択リストがある場合
    if ( list ) {
      // リストは「コンマ」区切りでItemID保管されているので分解
      var listSelected = list.split(",");

     // Itemlist内のアイテムのループ処理
      $("#itemlist .item .item-select input[type=checkbox]").each(function() {
        // 要素からitemIdを取得
        itemId = $(this).data("itemid");
        
        // 選択リストにItemIDが存在する場合はチェックを入れる
        if ($.inArray(itemId, listSelected) != -1) {
          $(this).prop("checked", true);
        }
      });
    }
    
    // リストデータの更新(金額の計算、数の表示）
    AppSelection.updateList();
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
      debug              : true,
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
  updateList : function() {
    // updateList : リストデータを更新する
    // 使う変数の定義
    var listSelected = [];
    var totalprice = 0;
    var totalselected = 0;

    // チェックされているアイテムのループ処理
    $("#itemlist .item .item-select input[type=checkbox]:checked").each(function() {
      // 要素のdata-priceから金額を取得し数値化
      price = parseInt($(this).data("price"));
      // トータル金額、総計を計算
      totalprice += price;
      totalselected++;
      // リスト配列に追加する
      listSelected.push($(this).data("itemid"));
    });

    // 金額合計要素に合計金額を表示
    $(".block_selected_price").text(totalprice.toLocaleString());
    // 選択合計要素に総計を表示
    $(".block_selected_item").text(totalselected);
    
    // 選択リスト配列を「コンマ」で結合し文字列化する
    var cookievalue = listSelected.join(",");
    
    // Cookieに保存
    docCookies.setItem("mn_cleaning_list", cookievalue);
  },
  check : function() {
    // チェックされているアイテムがなければボタンを有効/無効にする
    if ($("#itemlist .item .item-select input[type=checkbox]:checked").length == 0) {
      $(".item_confirm").addClass("disabled");
    } else {
      $("#flashMessage").slideUp("fast");
      $("#flashMessage").remove();
      $(".item_confirm").removeClass("disabled");
    }
  },
};
