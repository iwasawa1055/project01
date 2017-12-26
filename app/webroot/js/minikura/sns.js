var Act_sns = 
{
    /*
     * share button , FaceBook
     *
     * */
    a : function(){
        //* FB shareの時、og:image 対策する 元画像url 
        var image_url = $('.item').attr('src');

        //* fb_ogp.js　deferredで非同期test 　使用しない間はクラス名をdummyにしておく
        //$(document).on('click', '.btn-facebook-dummy', function(event){

        //* 画像処理とupload 
        $(document).on('click', '.btn-facebook', function(event){
            // //* image process
            // $.ajax({
            //     type: 'post',
            //     dataType: 'text',
            //     url: '/item/ajax_image_up',
            //     data: {
            //         'image_url' : image_url,
            //     },
            // }).done(function (data, textStatus, errorThrown) {
            //     console.log(data);
            // }).fail(function(data, textStatus, errorThrown){
            //     console.log(textStatus);
            //     alert('FB画像の処理にエラーが発生しました');
            // }).always(function(data){
            // });

            //* sns share
            var href = $(this).attr('href'); 
            window.open(encodeURI(decodeURI(href)),'_blank',
                        'width=550, height=450, personalbar=0, toolbar=0, scrollbars=1, resizable=!'); 
            event.preventDefault();
            return false;
                                          
        });
    },

    /*
     * share button , Twitter
     *
     * */
    b : function(){

        //* sns share
        $(document).on('click', '.btn-twitter', function(event){
            var href = $(this).attr('href'); 
            window.open(encodeURI(decodeURI(href)),'_blank',
                        'width=550, height=450, personalbar=0, toolbar=0, scrollbars=1, resizable=!'); 
            event.preventDefault();
            return false;
                                          
        });
    },

    /*
     * copy to clipboard
     *
     * */
    c : function(){
        $(document).on('click', '.btn-copy-sns', function(){
            var copyDiv = document.getElementById('copy-sns-url');
            copyDiv.focus();
            document.execCommand("SelectAll");
            document.execCommand("Copy", false, null);
        });

    },

    d : function(){
        $(document).on('click', '.btn-copy-tag', function(){
            var copyDiv = document.getElementById('copy-tag');
            copyDiv.focus();
            document.execCommand("SelectAll");
            document.execCommand("Copy", false, null);
        });

    },

}

/*
 *
 * $(document).ready(function(){});
 * */
$(function(){
    Act_sns.a();
    Act_sns.b();
    Act_sns.c();
    Act_sns.d();
});

