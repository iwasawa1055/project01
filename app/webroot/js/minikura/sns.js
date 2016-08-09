var Act_sns = 
{
    /*
     * share button , FB/Twitter
     *
     * */
    a : function(){
        $(document).on('click', '.btn-facebook , .btn-twitter', function(event){
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
    b : function(){
        $(document).on('click', '.btn-copy-sns', function(){
            var copyDiv = document.getElementById('copy-sns-url');
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
});

