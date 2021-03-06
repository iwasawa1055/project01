/*
 * libGmoCreditCardPayment.js
 */
var libGmoCreditCardPayment = function(){

    this.gmoTokenCode = {
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

    this.convertGMOTokenCodeIntoString = function(code){
        return this.gmoTokenCode[code];
    }

    this.validate = function(params){
        var errors = {};

        if(params.securitycode === ''){
            errors.securitycode = {
                value: params.securitycode,
                message: "セキュリティコードが入力されていません。"
            };
        }

        if(params.expire === ''){
            checkValidation = false;
            errors.expire = {
                value: params.expire,
                message: "カード有効期限が入力されていません。"
            };
        }

        if(params.expire !== ''){
            var date = new Date();
            var date1 = new Date(date.getFullYear(),date.getMonth());
            var date2;

            // 4桁の場合
            if(params.expire.length === 4){
                date2 = new Date(20+params.expire.substr(0,2), params.expire.substr(2,2)-1);
            // 6桁の場合
            } else if(params.expire.length === 6) {
                date2 = new Date(params.expire.substr(0,4), params.expire.substr(3,2)-1);
            }

            if(date1 > date2){
                checkValidation = false;
                errors.holdername = {
                    value: params.holdername,
                    message: "カード有効期限をご確認ください。"
                };
            }
        }

        if(params.holdername === ''){
            checkValidation = false;
            errors.holdername = {
                value: params.holdername,
                message: "カード名義が入力されていません。"
            };
        }

        return errors;
    }
}
