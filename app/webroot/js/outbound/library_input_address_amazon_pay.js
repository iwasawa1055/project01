var AppAmazonPay =
{
    a: function () {
        $('#execute').on('click', function (e) {
            var add_billing  = $('<input type="hidden" name="amazon_order_reference_id">');
            add_billing.val(AppAmazonPayWallet.AmazonOrderReferenceId);
            $('#form').append(add_billing);
            document.form.submit();
        });
    },
    ajax_dateime: function () {
        var elem_datetime = $('#datetime_cd');
        if (elem_datetime.length == 0) {
            return;
        }

        $('option:first', elem_datetime).prop('selected', true);
        elem_datetime.attr("disabled", "disabled");

        // 引数取得
        var params = {};
        params.amazon_order_reference_id = AppAmazonPayWallet.AmazonOrderReferenceId;
        params.trunk_cds = JSON.parse($('#trunkCds').val());

        // API実行
        if (params.postal != '') {
            $.ajax({
                url: '/Outbound/getAddressDatetimeByAmazon',
                cache: false,
                data: params,
                dataType: 'json',
                type: 'POST'
            }).done(function (data, textStatus, jqXHR) {
                $('#datetime_cd > option').remove();
                // 成功時 お届け日時セット
                $.each(data.result, function (index, datatime) {
                    elem_datetime.append($('<option>').html(datatime.text).val(datatime.datetime_cd));
                });
            }).fail(function (data, textStatus, errorThrown) {
                // 失敗時 お届け日時リセット
                $('#datetime_cd > option').remove();
            }).always(function (data, textStatus, returnedObject) {
                elem_datetime.removeAttr("disabled");
            });
        } else {
            // お届け日時リセット
            $('#datetime_cd > option').remove();
            elem_datetime.removeAttr("disabled");
        }
    }
}

var AppAmazonPayWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonOrderReferenceId: '',
    buyerBillingAgreementConsentStatus: false,
    AmazonWidgetReadyFlag: false,

    a: function () {
        // amazon Widget Ready
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppAmazonPayWallet.ClientId);
            AppAmazonPayWallet.b();
        };
    },
    b: function () {
        // アドレスWidgetを表示
        new OffAmazonPayments.Widgets.AddressBook({
            sellerId: AppAmazonPayWallet.SELLER_ID,

            onOrderReferenceCreate: function(orderReference) {
                // Here is where you can grab the Order Reference ID.
                AppAmazonPayWallet.AmazonOrderReferenceId = orderReference.getAmazonOrderReferenceId();
            },
            // Widgets起動状態
            onReady: function() {
                AppAmazonPayWallet.AmazonWidgetReadyFlag = true;

                // カード選択 Widgetを表示
                new OffAmazonPayments.Widgets.Wallet({
                    sellerId: AppAmazonPayWallet.SELLER_ID,
                    design: {
                        designMode: 'responsive'
                    },
                    // カード選択変更時
                    onPaymentSelect: function (orderReference) {
                    },
                    onError: function (error) {
                        console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                    }
                }).bind("walletWidgetDiv");

            },
            // 住所選択変更時
            onAddressSelect: function () {
                // do stuff here like recalculate tax and/or shipping
                // お届希望日を取得
                AppAmazonPay.ajax_dateime();
            },
            design: {
                designMode: 'responsive'
            },
            onError: function (error) {
                if(error.getErrorCode() == 'BuyerSessionExpired') {
                    amazon.Login.logout();
                    location.href = '/login/logout';
                }
            }
        }).bind("addressBookWidgetDiv");
    }
}

// amazonpay callback
AppAmazonPayWallet.a();

/*
 * document ready
 * */
$(function()
{
  AppAmazonPay.a();
});
