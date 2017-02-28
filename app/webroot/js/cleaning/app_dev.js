$(function () {
  AppCleaning.initialize();
  $('.item_confirm').click(function() { 
    $("#itemlist").submit();
  });

  $(document).on("change",".item-select input[type=checkbox]",function(){
    AppCleaning.updateList();
  });
  
  $("#ClearSelected").click(function() {
    $("#itemlist .item .item-select input[type=checkbox]:checked").prop("checked",false);
    AppCleaning.updateList();
  });



});

var AppCleaning = {
  initialize : function() {
    var list = docCookies.getItem("mn_cleaning_list");
    var selectedItem = $("#ItemSelected").val();
    
    if ( list || selectedItem ) {
      if ( list ) {
        var listSelected = list.split(",");
      } else {
        var listSelected = [selectedItem];
      }

      $(".grid ul").infinitescroll({
        dataType  : "html",
        navSelector  : ".pagination ",
        nextSelector : ".next a",
        itemSelector : "#itemlist ul li",
        finishedMsg  : "",
        msgText : "",
        img : null,
	  prefill : true,
	},function(){
          $("#itemlist .item .item-select input[type=checkbox]").each(function() {
            itemId = $(this).data("itemid");
            if ( $.inArray(itemId, listSelected) != -1  ) {
              $(this).prop("checked",true);
            }
          });
          AppCleaning.updateList();
          $('.remodal').remodal();
        });
    } else {
      $(".grid ul").infinitescroll({
        dataType  : "html",
        navSelector  : ".pagination ",
        nextSelector : ".next a",
        itemSelector : "#itemlist ul li",
        finishedMsg  : "",
        msgText     : "",
	},function(){
          $('.remodal').remodal();
	});
    }
  },
  updateList : function() {
    var listSelected = [];
    
    var totalprice = 0;
    var totalselected = 0;
    $("#itemlist .item .item-select input[type=checkbox]").each(function() {
      if ( $(this).is(':checked')) {
        price = parseInt($(this).data("price"));
        totalprice += price;
        totalselected++;
        listSelected.push($(this).data("itemid"));
      }
    });
    
    $(".block_selected_price").text(totalprice.toLocaleString());
    $(".block_selected_item").text(totalselected);
    
    var cookievalue = listSelected.join(",");
    docCookies.setItem("mn_cleaning_list",cookievalue);
  },
};

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