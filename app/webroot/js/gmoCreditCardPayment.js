/*
 * gmoCreditCardPayment.js
 */

var gmoCreditCardPayment = {
    libGmoCreditCardPayment : null,
    shopId : null,
    tokenResponces : null,
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
        }
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
        $(selector).prepend('<span style="font-size: 1.6rem; color: #ff6666; padding: 0.5rem; margin: 0.5rem 0 0;">'+message+'</span>');
    },
    checkTokenResponce: function(){
        if(gmoCreditCardPayment.tokenResponces.resultCode !== '000'){
            gmoCreditCardPayment.displayMessage(
                gmoCreditCardPayment.libGmoCreditCardPayment.convertGMOTokenCodeIntoString(gmoCreditCardPayment.tokenResponces.resultCode),
                gmoCreditCardPayment.validateErrorSelector
            );
            $('.airloader-overlay').hide();
            return new $.Deferred().reject().promise();
        }
        return new $.Deferred().resolve().promise();
    },
    getToken: function(){
        var d = new $.Deferred;
        Multipayment.init(gmoCreditCardPayment.shopId);

        callbackFunction = function(responces){
          console.log(responces);
            gmoCreditCardPayment.tokenResponces = responces;
            d.resolve();
        }
        Multipayment.getToken({
            cardno: gmoCreditCardPayment.params.cardno,
            expire: gmoCreditCardPayment.params.expire,
            securitycode: gmoCreditCardPayment.params.securitycode,
            holdername: gmoCreditCardPayment.params.holdername,
            tokennumber: '10'
        }, callbackFunction);
        return d.promise();
    },
    setTokenToHiddenParam: function(){
        // 既にgmo_tokenが存在していた場合は削除
        if($('[name=gmo_token]').length){
            $('[name=gmo_token]').remove();
        }
        $('form').append('<input type="hidden" name="gmo_token" value="'+gmoCreditCardPayment.tokenResponces.tokenObject.token+'">');

        return new $.Deferred().resolve().promise();
    },
    setTokenToHiddenParamForCheck: function(){
        if($('[name=gmo_token_for_check]').length){
            $('[name=gmo_token_for_check]').remove();
        }
        $('form').append('<input type="hidden" name="gmo_token_for_check" value="'+gmoCreditCardPayment.tokenResponces.tokenObject.token+'">');

        return new $.Deferred().resolve().promise();
    },
    checkCreditCard: function(){
        var d = new $.Deferred;
        $.ajax({
            type: "POST",
            url: "/order/check_credit_card",
            data: {
                "gmo_token": gmoCreditCardPayment.tokenResponces.tokenObject.token
            }
        })
        .then(
            // 通信成功
            function(jsonCheckResponce){
                var checkResponce = JSON.parse(jsonCheckResponce);
                console.log(checkResponce);
                if(checkResponce.status == true){
                    // カード確認OK
                    console.log(checkResponce);
                    d.resolve();
                }else{
                    $('.airloader-overlay').hide();
                    gmoCreditCardPayment.displayMessage(checkResponce.error_message, gmoCreditCardPayment.creditCardInfoSelector);
                    d.reject();
                }
            },
            // 通信失敗
            function(){
                //　通信失敗
                $('.airloader-overlay').hide();
                d.reject();
            }
        );
        return d.promise();
    },
    registerCreditCard: function(){
        var d = new $.Deferred;
        $.ajax({
            type: "POST",
            url: "/order/register_credit_card",
            data: {
                "gmo_token": gmoCreditCardPayment.tokenResponces.tokenObject.token
            }
        })
        .then(
            // 通信成功
            function(jsonRegisterResponce){
                console.log(jsonRegisterResponce);
                var registerResponce = JSON.parse(jsonRegisterResponce);
                console.log(registerResponce);
                if(registerResponce.status == true){
                    gmoCreditCardPayment.displayMessage("クレジットカードの登録に成功しました。", gmoCreditCardPayment.creditCardInfoSelector);
                    $('.dsn-select-cards').css("display","block");
                    $("#as-card").prop('checked', true);
                    $('label[for=as-card]').text(registerResponce.results.card_no);
                    $('input[name=security_cd]').val($('input[name=securitycode]').val());
                    $('.dsn-input-security-code').toggle('slow');
                    $('.dsn-input-change-card').hide('slow');
                    $('.dsn-input-new-card').hide('slow');
                    $('#execute').off('click');
                    $('#execute').on('click', function (e) {
                      gmoCreditCardPayment.setGMOTokenAndUpdateCreditCard();
                    });
                    $('.airloader-overlay').hide();
                    d.resolve();
                }else{
                    $('.airloader-overlay').hide();
                    gmoCreditCardPayment.displayMessage("クレジットカードの登録に失敗しました。", gmoCreditCardPayment.creditCardInfoSelector);
                    d.reject();
                }
            },
            // 通信失敗
            function(){
                //　通信失敗
                $('.airloader-overlay').hide();
                d.reject();
            }
        );
        return d.promise();
    },
    updateCreditCard: function(){
        var d = new $.Deferred;
        $.ajax({
            type: "POST",
            url: "/order/update_credit_card",
            data: {
                "gmo_token": gmoCreditCardPayment.tokenResponces.tokenObject.token
            }
        })
        .then(
            // 通信成功
            function(jsonUpdateResponce){
                var updateResponce = JSON.parse(jsonUpdateResponce);
                console.log(updateResponce);
                if(updateResponce.status == true){
                    gmoCreditCardPayment.displayMessage("クレジットカードの登録に成功しました。", gmoCreditCardPayment.creditCardInfoSelector);
                    $("#as-card").prop('checked', true);
                    $("#change-card").prop('checked', false);
                    $('label[for=as-card]').text(updateResponce.results.card_no);
                    $('input[name=security_cd]').val($('input[name=securitycode]').val());
                    $('.dsn-input-security-code').toggle('slow');
                    $('.dsn-input-change-card').hide('slow');
                    $('.dsn-input-new-card').hide('slow');
                    $('.airloader-overlay').hide();
                    d.resolve();
                }else{
                    $('.airloader-overlay').hide();
                    gmoCreditCardPayment.displayMessage("クレジットカードの登録に失敗しました。", gmoCreditCardPayment.creditCardInfoSelector);
                    d.reject();
                }
            },
            // 通信失敗
            function(){
                //　通信失敗
                $('.airloader-overlay').hide();
                d.reject();
            }
        );
        return d.promise();
    },
    setGMOTokenAndSubmit: function() {
        this.init();

        this.validate()
        // for card check
        .then(this.getToken)
        .then(this.checkTokenResponce)
        .then(this.setTokenToHiddenParamForCheck)

        .then(this.getToken)
        .then(this.checkTokenResponce)
        .then(this.setTokenToHiddenParam)
        .then(function(){
            $('form').submit();
        });
    },
    setGMOTokenAndRegisterCreditCard: function(){
        this.init();

        this.validate()
        // for card check
        .then(this.getToken)
        .then(this.checkTokenResponce)
        .then(this.checkCreditCard)

        .then(this.getToken)
        .then(this.checkTokenResponce)
        .then(this.registerCreditCard)
    },
    setGMOTokenAndUpdateCreditCard: function(){
        this.init();

        this.validate()
        // for card check
        .then(this.getToken)
        .then(this.checkTokenResponce)
        .then(this.checkCreditCard)

        .then(this.getToken)
        .then(this.checkTokenResponce)
        .then(this.updateCreditCard)
    },
}
