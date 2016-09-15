/**
 * deferredで非同期処理。画像の加工とupload後に、facebook share
 * 現在未使用 
 * */

var Act_fb =
{
    /*
     * share button , FaceBook
     *
     * */
    share : function(query){
        //* Deferred
        var defer = $.Deferred();
        //* FB shareの時、og:image 対策処理 元画像
        var image_url = $('.item').attr('src');

        $.ajax({
            type: 'post',
            dataType: 'text',
            url: '/item/ajax_image_up',
            data: {
                'image_url' : image_url,
            },
            success: defer.resolve,
            error: defer.reject
         });
        return defer.promise();
    }
};

//* click
//* 元 
//$('.btn-facebook').on('click', function(event){

//* 使用しない間は、クラス名をdummyにしておく
$('.btn-facebook-dummy').on('click', function(event){
   //* fb url
    var href = $(this).attr('href');
    //console.log('dummy');
    //* sns share
    Act_fb.share('jquery deferred').done(function(data){
        //console.log(data);
        $('.fb-window').prop('href', href);
        $('.fb-window').prop('target', '_blank');
        $('.fb-window').trigger('click');
        //window.open(encodeURI(decodeURI(href)),'_blank',
        //  'width=550, height=450, personalbar=0, toolbar=0, scrollbars=1, resizable=!');
        //event.preventDefault();
        //return false;
    });
});
