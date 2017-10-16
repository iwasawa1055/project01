/*!
 * gmoCreditPayment.js
 *
 * 使い方は下のリンクにまとめました。
 * https://red01.minikulab.com/projects/minikura-memo/wiki/GMO_PAYMENT%E7%94%A8%E3%81%AEJquery_Plugin%E3%81%AE%E4%BD%BF%E3%81%84%E6%96%B9
 */

(function( $ ){
    var $this;

    var settings = {
        shopId: '',
        convertHalfSize: true,
        validationInnerElm: '<p class="validation" />',
    }

    var params = {
        cardno: '',
        expire: '',
        securitycode: '',
        holdername: '',
    }

    var checkValidation = false;

    var tokenResponses;

    var methods = {
        init : function( options ) {
            return this.each(function(){
                $this = $(this);

                settings = $.extend(settings, options);

                methods.setParams();

                if(settings.convertHalfSize === true) {
                    params = methods.convertHalfSize(params);
                }

                console.log(params);
            });
        },
        setParams : function() {
            params = {
                cardno: $this.find("#cardno").val(),
                expire: $this.find("#expireyear").val() + $this.find("#expiremonth").val(),
                securitycode: $this.find("#securitycode").val(),
                holdername: $this.find("#holdername").val(),
            }
        },
        convertHalfSize : function() {
            // cardno(全角数字=>半角数字変換)
            params.cardno = params.cardno.replace(/[０-９]/g, function(s) {
                return String.fromCharCode(s.charCodeAt(0) - 65248);
            });

            // cardno(全角/半角ハイフン削除)
            params.cardno = params.cardno.replace( /(-|ー)/g , "");

            // securitycode(全角数字=>半角数字変換)
            params.securitycode = params.securitycode.replace(/[０-９]/g, function(s) {
                return String.fromCharCode(s.charCodeAt(0) - 65248);
            });

            return params;

        },
        validate : function() {
            checkValidation = true;
            var errors = {};

            // エラーメッセージの初期化
            $.each(params,function(k,v){
                $this.find('#error_' + k).empty();
            });

            if(params.cardno === ''){
                checkValidation = false;
                errors.cardno = {
                    value: params.cardno,
                    message: "クレジットカード番号が入力されていません。"
                };
            }

            if(params.cardno.match(/[^0-9]+/) !== null){
                checkValidation = false;
                errors.cardno = {
                    value: params.cardno,
                    message: "クレジットカード番号は数字（ハイフン付きも可）で入力してください。"
                };
            }

            if(params.securitycode === ''){
                checkValidation = false;
                errors.securitycode = {
                    value: params.securitycode,
                    message: "セキュリティコードが入力されていません。"
                };
            }

            if(params.securitycode.match(/^[0-9]{3,4}$/) === null){
                checkValidation = false;
                errors.securitycode = {
                    value: params.securitycode,
                    message: "セキュリティコードは3桁または4桁の数字で入力してください。"
                };
            }

            if(params.expire === ''){
                checkValidation = false;
                errors.expire = {
                    value: params.expire,
                    message: "カード有効期限が入力されていません。"
                };
            }

            if(params.expire.match(/^[0-9]{4,6}$/) === null){
                checkValidation = false;
                errors.expire = {
                    value: params.expire,
                    message: "カード有効期限は4桁または6桁の数字で入力してください。"
                };
            }

            if(params.holdername === ''){
                checkValidation = false;
                errors.holdername = {
                    value: params.holdername,
                    message: "カード名義が入力されていません。"
                };
            }

            if(params.holdername.match(/^[0-9a-zA-Z ,.-\/]{1,50}$/) === null){
                checkValidation = false;
                errors.holdername = {
                    value: params.holdername,
                    message: "カード名義は50文字以内の半角文字で入力してください。"
                };
            }

            // エラーメッセージを表示
            if(checkValidation === false){
                $.each(errors,function(k,v){
                    $this.find('#error_' + k).text(v.message).wrapInner(settings.validationInnerElm);
                });
            }
        },
        setGMOTokenAndSubmit: function(){
            return this.each(function(){
                methods.validate();

                if(checkValidation === false){
                    return;
                }
                var promise = methods.getToken();
                promise.done(function(){
                    console.log(tokenResponses);
                    if(tokenResponses.resultCode === '000'){
                        $this.append('<input type="hidden" name="gmo_token" value="'+tokenResponses.tokenObject.token+'">');
                        $this.submit();
                    } else {
                        alert(methods.convertGMOError(tokenResponses.resultCode));
                    }
                });
            });
        },
        getToken: function(){
            Multipayment.init(settings.shopId);

            var d = new $.Deferred;
            callbackFunction = function(responses){
                tokenResponses = responses;
                d.resolve();
            }

            Multipayment.getToken({
                cardno: params.cardno,
                expire: params.expire,
                securitycode: params.securitycode,
                holdername: params.holdername,
                tokennumber: '10'
            }, callbackFunction);
            return d.promise();
        },
        convertGMOError: function(c){
            code = {
               '000': 'トークン取得正常終了',
               '100': 'カード番号必須チェックエラー',
               '101': 'カード番号フォーマットエラー(数字以外を含む)',
               '102': 'カード番号フォーマットエラー(10-16 桁の範囲外)',
               '110': '有効期限必須チェックエラー',
               '111': '有効期限フォーマットエラー(数字以外を含む)',
               '112': '有効期限フォーマットエラー(6 又は 4 桁以外)',
               '113': '有効期限フォーマットエラー(月が 13 以上)',
               '121': 'セキュリティコードフォーマットエラー(数字以外を含む)',
               '122': 'セキュリティコードフォーマットエラー(5 桁以上)',
               '131': '名義人フォーマットエラー(半角英数字、一部の記号以外を含む)',
               '132': '名義人フォーマットエラー(51 桁以上)',
               '141': '発行数フォーマットエラー(数字以外を含む)',
               '142': '発行数フォーマットエラー(1-10 の範囲外)',
               '150': 'カード情報を暗号化した情報必須チェックエラー',
               '160': 'ショップ ID 必須チェックエラー',
               '161': 'ショップ ID フォーマットエラー(14 桁以上)',
               '162': 'ショップ ID フォーマットエラー(半角英数字以外)',
               '170': '公開鍵ハッシュ値必須チェックエラー',
               '180': 'ショップ ID または公開鍵ハッシュ値がマスターに存在しない',
               '190': 'カード情報(Encrypted)が復号できない',
               '191': 'カード情報(Encrypted)復号化後フォーマットエラー',
               '501': 'トークン用パラメータ(id)が送信されていない',
               '502': 'トークン用パラメータ(id)がマスターに存在しない',
               '511': 'トークン用パラメータ(cardInfo)が送信されていない',
               '512': 'トークン用パラメータ(cardInfo)が復号できない',
               '521': 'トークン用パラメータ(key)が送信されていない',
               '522': 'トークン用パラメータ(key)が復号できない',
               '531': 'トークン用パラメータ(callBack)が送信されていない',
               '541': 'トークン用パラメータ(hash)が存在しない',
               '551': 'トークン用 apikey が存在しない ID',
               '552': 'トークン用 apikey が有効ではない',
               '553': 'トークンが利用済みである。',
               '901': 'マルチペイメント内部のシステムエラー',
            }
            return code[c];
        }
    };

    $.fn.gmoCreditPayment = function( method ) {

        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.gmoCreditPayment' );
        }

    };

})( jQuery );
