var AppCustomerGvidoCard =
{
    a: function () {
        $('#execute').on('click', function (e) {
            gmoCreditCardPayment.setGMOTokenAndSubmit();
        });
    }
};

/*
 * gmoCreditCardPayment.js
 */

// GMOのcallBack用関数(グローバルでないとIE11で動かない)
function gmoCallbackFunction(responses){
    gmoCreditCardPayment.tokenResponses = responses;
    gmoCreditCardPayment.gmoCallbackDefferd.resolve();
}

var gmoCreditCardPayment = {
    libGmoCreditCardPayment : null,
    shopId : null,
    tokenResponses : null,
    gmoCallbackDefferd : null,
    validateErrorSelector: '#gmo_validate_error',
    creditCardInfoSelector: '#gmo_credit_card_info',
    params : {
        cardno: '',
        expire: '',
        securitycode: '',
        holdername: '',
    },
    init : function() {
        $('.loader').airCenter();
        $('.airloader-overlay').show();
        $(this.validateErrorSelector).empty();
        $(this.creditCardInfoSelector).empty();
        this.libGmoCreditCardPayment = new libGmoCreditCardPayment();
        this.setShopId();
        this.setParams();
        this.convertHalfSize();
    },
    setParams : function() {
        this.params = {
            cardno: $("#cardno").val(),
            expire: $("#expireyear").val() + $("#expiremonth").val(),
            securitycode: $("#securitycode").val(),
            holdername: $("#holdername").val(),
        };
    },
    setShopId : function() {
        this.shopId = $('#shop_id').val();
    },
    convertHalfSize : function() {
        // cardno(全角数字=>半角数字変換)
        this.params.cardno = this.params.cardno.replace(/[０-９]/g, function(s) {
            return String.fromCharCode(s.charCodeAt(0) - 65248);
        });

        // cardno(全角/半角ハイフン削除)
        this.params.cardno = this.params.cardno.replace( /(-|ー)/g , "");

        // securitycode(全角数字=>半角数字変換)
        this.params.securitycode = this.params.securitycode.replace(/[０-９]/g, function(s) {
            return String.fromCharCode(s.charCodeAt(0) - 65248);
        });
    },
    wait : function() {
        var d = new $.Deferred();
        setTimeout(d.resolve, 500);
        return d;
    },
    validate : function() {
        var errors = gmoCreditCardPayment.libGmoCreditCardPayment.validate(gmoCreditCardPayment.params);

        // エラーメッセージを表示
        if(Object.keys(errors).length){
            $.each(errors,function(k,v){
                //$('#error_' + k).text(v.message).wrapInner('<p />');
                gmoCreditCardPayment.displayMessage(v.message, gmoCreditCardPayment.validateErrorSelector);
                return false;
            });
            $('.airloader-overlay').hide();
            return new $.Deferred().reject().promise();
        }
        return new $.Deferred().resolve().promise();
    },
    displayMessage: function(message, selector){
        $(selector).prepend('<p class="valid-il">'+message+'</p>');
    },
    checkTokenResponse: function(){
        if(gmoCreditCardPayment.tokenResponses.resultCode !== '000'){
            gmoCreditCardPayment.displayMessage(
                gmoCreditCardPayment.libGmoCreditCardPayment.convertGMOTokenCodeIntoString(gmoCreditCardPayment.tokenResponses.resultCode),
                gmoCreditCardPayment.validateErrorSelector
            );
            $('.airloader-overlay').hide();
            return new $.Deferred().reject().promise();
        }
        return new $.Deferred().resolve().promise();
    },
    getToken: function(){
        var d = new $.Deferred();
        gmoCreditCardPayment.gmoCallbackDefferd = d;
        Multipayment.init(gmoCreditCardPayment.shopId);
        Multipayment.getToken({
            cardno: gmoCreditCardPayment.params.cardno,
            expire: gmoCreditCardPayment.params.expire,
            securitycode: gmoCreditCardPayment.params.securitycode,
            holdername: gmoCreditCardPayment.params.holdername,
            tokennumber: '10'
        }, gmoCallbackFunction);
        return d.promise();
    },
    setTokenToHiddenParam: function(){
        // 既にgmo_tokenが存在していた場合は削除
        if($('[name=gmo_token]').length){
            $('[name=gmo_token]').remove();
        }
        $('form').append('<input type="hidden" name="gmo_token" value="'+gmoCreditCardPayment.tokenResponses.tokenObject.token+'">');

        return new $.Deferred().resolve().promise();
    },
    setTokenToHiddenParamForCheck: function(){
        if($('[name=gmo_token_for_check]').length){
            $('[name=gmo_token_for_check]').remove();
        }
        $('form').append('<input type="hidden" name="gmo_token_for_check" value="'+gmoCreditCardPayment.tokenResponses.tokenObject.token+'">');

        return new $.Deferred().resolve().promise();
    },
    setGMOTokenAndSubmit: function() {
        this.init();

        this.wait()
        .then(this.validate)

        // for card check
        .then(this.getToken)
        .then(this.checkTokenResponse)
        .then(this.setTokenToHiddenParamForCheck)

        .then(this.getToken)
        .then(this.checkTokenResponse)
        .then(this.setTokenToHiddenParam)
        .then(function(){
            $('form').submit();
        });
    },
};

/*
 * document ready
 * */
$(function()
{
    AppCustomerGvidoCard.a();
});
