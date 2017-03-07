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
    $(document).on("change",".item-select input[type=checkbox]",function(){
      AppSelection.updateList();
    });
  },
  b : function() {
    // クリアーボタンが押された時の処理
    $("#ClearSelected").click(function() {
      $("#itemlist .item .item-select input[type=checkbox]:checked").prop("checked",false);
      AppSelection.updateList();
    });
  },
  c : function() {
    // 確認ボタンが押された場合の処理
    $('.item_confirm').click(function() { 
      $("#itemlist").submit();
    });
  },
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
        if ( $.inArray(itemId, listSelected) != -1  ) {
          $(this).prop("checked",true);
        }
      });
    }
    
    // リストデータの更新(金額の計算、数の表示）
    AppSelection.updateList();
   
    // Infinitescroll
    $(".grid ul").infinitescroll({
      dataType         : "html",
      navSelector    : ".pagination ",
      nextSelector   : ".next a",
      itemSelector   : "#itemlist ul li",
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
    docCookies.setItem("mn_cleaning_list",cookievalue);
  },
};

// 外部スクリプト
//   https://developer.mozilla.org/ja/docs/Web/API/Document/cookie
var docCookies = {
  getItem: function (sKey) {
    if (!sKey || !this.hasItem(sKey)) { return null; }
    return unescape(document.cookie.replace(new RegExp("(?:^|.*;\\s*)" + escape(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*((?:[^;](?!;))*[^;]?).*"), "$1"));
  },
  setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
    if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return; }
    var sExpires = "";
    if (vEnd) {
      switch (vEnd.constructor) {
        case Number:
          sExpires = vEnd === Infinity ? "; expires=Tue, 19 Jan 2038 03:14:07 GMT" : "; max-age=" + vEnd;
          break;
        case String:
          sExpires = "; expires=" + vEnd;
          break;
        case Date:
          sExpires = "; expires=" + vEnd.toGMTString();
          break;
      }
    }
    document.cookie = escape(sKey) + "=" + escape(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
  },
  removeItem: function (sKey, sPath) {
    if (!sKey || !this.hasItem(sKey)) { return; }
    document.cookie = escape(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sPath ? "; path=" + sPath : "");
  },
  hasItem: function (sKey) {
    return (new RegExp("(?:^|;\\s*)" + escape(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
  },
  keys: /* optional method: you can safely remove it! */ function () {
    var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
    for (var nIdx = 0; nIdx < aKeys.length; nIdx++) { aKeys[nIdx] = unescape(aKeys[nIdx]); }
    return aKeys;
  }
};