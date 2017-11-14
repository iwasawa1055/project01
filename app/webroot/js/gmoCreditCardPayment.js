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
        $(selector).prepend('<span style="font-size: 1.6rem; color: #ff6666; padding: 0.5rem; margin: 0.5rem 0 0;">'+message+'</span>');
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
    checkCreditCard: function(){
        var d = new $.Deferred();
        $.ajax({
            type: "POST",
            url: "/order/check_credit_card",
            data: {
                "gmo_token": gmoCreditCardPayment.tokenResponses.tokenObject.token
            }
        })
        .then(
            // 通信成功
            function(jsonCheckResponse){
                var checkResponse = JSON.parse(jsonCheckResponse);
                if(checkResponse.status == true){
                    // カード確認OK
                    d.resolve();
                }else{
                    $('.airloader-overlay').hide();
                    gmoCreditCardPayment.displayMessage(checkResponse.error_message, gmoCreditCardPayment.creditCardInfoSelector);
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
    checkCreditCardForCreditChange: function(){
        var d = new $.Deferred();
        $.ajax({
            type: "POST",
            url: "/paymentng/credit_card/as_check_credit_card",
            data: {
                "gmo_token": gmoCreditCardPayment.tokenResponses.tokenObject.token
            }
        })
        .then(
            // 通信成功
            function(jsonCheckResponse){
                var checkResponse = JSON.parse(jsonCheckResponse);
                if(checkResponse.status == true){
                    // カード確認OK
                    d.resolve();
                }else{
                    $('.airloader-overlay').hide();
                    gmoCreditCardPayment.displayMessage(checkResponse.error_message, gmoCreditCardPayment.creditCardInfoSelector);
                    d.reject();
                }
            },
            // 通信失敗
            function(){
                //　通信失敗
                gmoCreditCardPayment.displayMessage('通信に失敗しました。', gmoCreditCardPayment.creditCardInfoSelector);
                $('.airloader-overlay').hide();
                d.reject();
            }
        );
        return d.promise();
    },
    registerCreditCard: function(){
        var d = new $.Deferred();
        $.ajax({
            type: "POST",
            url: "/order/register_credit_card",
            data: {
                "gmo_token": gmoCreditCardPayment.tokenResponses.tokenObject.token
            }
        })
        .then(
            // 通信成功
            function(jsonRegisterResponse){
                var registerResponse = JSON.parse(jsonRegisterResponse);
                if(registerResponse.status == true){
                    gmoCreditCardPayment.displayMessage("クレジットカードの登録に成功しました。", gmoCreditCardPayment.creditCardInfoSelector);
                    $('.dsn-select-cards').css("display","block");
                    $("#as-card").prop('checked', true);
                    $('label[for=as-card]').text(registerResponse.results.card_no);
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
                gmoCreditCardPayment.displayMessage('通信に失敗しました。', gmoCreditCardPayment.creditCardInfoSelector);
                $('.airloader-overlay').hide();
                d.reject();
            }
        );
        return d.promise();
    },
    registerCreditCardForCreditChange: function(){
        var d = new $.Deferred();
        $.ajax({
            type: "POST",
            url: "/paymentng/credit_card/as_register_credit_card",
            data: {
                "gmo_token": gmoCreditCardPayment.tokenResponses.tokenObject.token
            }
        })
        .then(
            // 通信成功
            function(jsonRegisterResponse){
                var registerResponse = JSON.parse(jsonRegisterResponse);
                if(registerResponse.status == true){
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
                gmoCreditCardPayment.displayMessage('通信に失敗しました。', gmoCreditCardPayment.creditCardInfoSelector);
                $('.airloader-overlay').hide();
                d.reject();
            }
        );
        return d.promise();
    },
    updateCreditCard: function(){
        var d = new $.Deferred();
        $.ajax({
            type: "POST",
            url: "/order/update_credit_card",
            data: {
                "gmo_token": gmoCreditCardPayment.tokenResponses.tokenObject.token
            }
        })
        .then(
            // 通信成功
            function(jsonUpdateResponse){
                var updateResponse = JSON.parse(jsonUpdateResponse);
                if(updateResponse.status == true){
                    gmoCreditCardPayment.displayMessage("クレジットカードの登録に成功しました。", gmoCreditCardPayment.creditCardInfoSelector);
                    $("#as-card").prop('checked', true);
                    $("#change-card").prop('checked', false);
                    $('label[for=as-card]').text(updateResponse.results.card_no);
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
                gmoCreditCardPayment.displayMessage('通信に失敗しました。', gmoCreditCardPayment.creditCardInfoSelector);
                $('.airloader-overlay').hide();
                d.reject();
            }
        );
        return d.promise();
    },
    updateCreditCardForCreditChange: function(){
        var d = new $.Deferred();
        $.ajax({
            type: "POST",
            url: "/paymentng/credit_card/as_update_credit_card",
            data: {
                "gmo_token": gmoCreditCardPayment.tokenResponses.tokenObject.token
            }
        })
        .then(
            // 通信成功
            function(jsonUpdateResponse){
                var updateResponse = JSON.parse(jsonUpdateResponse);
                if(updateResponse.status == true){
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
                gmoCreditCardPayment.displayMessage('通信に失敗しました。', gmoCreditCardPayment.creditCardInfoSelector);
                $('.airloader-overlay').hide();
                d.reject();
            }
        );
        return d.promise();
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
    setGMOTokenAndRegisterCreditCard: function(){
        this.init();

        this.wait()
        .then(this.validate)

        // for card check
        .then(this.getToken)
        .then(this.checkTokenResponse)
        .then(this.checkCreditCard)

        .then(this.getToken)
        .then(this.checkTokenResponse)
        .then(this.registerCreditCard);
    },
    setGMOTokenAndUpdateCreditCard: function(){
        this.init();

        this.wait()
        .then(this.validate)

        // for card check
        .then(this.getToken)
        .then(this.checkTokenResponse)
        .then(this.checkCreditCard)

        .then(this.getToken)
        .then(this.checkTokenResponse)
        .then(this.updateCreditCard);
    },
    setGMOTokenAndRegisterCreditCardAndSubmit: function(){
        this.init();

        this.wait()
        .then(this.validate)

        // for card check
        .then(this.getToken)
        .then(this.checkTokenResponse)
        .then(this.checkCreditCardForCreditChange)

        .then(this.getToken)
        .then(this.checkTokenResponse)
        .then(this.registerCreditCardForCreditChange)
        .then(function(){
            $('form').submit();
        });
    },
    setGMOTokenAndUpdateCreditCardAndSubmit: function(){
        this.init();

        this.wait()
        .then(this.validate)

        // for card check
        .then(this.getToken)
        .then(this.checkTokenResponse)
        .then(this.checkCreditCardForCreditChange)

        .then(this.getToken)
        .then(this.checkTokenResponse)
        .then(this.updateCreditCardForCreditChange)
        .then(function(){
            $('form').submit();
        });
    },
};
