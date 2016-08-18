var Act_sale = 
{
    /*
     * 販売履歴のselect status
     * */
    a : function(){
        $("select").change(function(event){
            var status_type = "";
            var href = "/sale/index";
            $("select option:selected").each(function(){
                status_type = $(this).val();
                console.log(status_type);
                location.href = href + '?status_type=' + status_type;
            });
            return false;
        });
    },


}

/*
 *
 * $(document).ready(function(){});
 * */
$(function(){
    Act_sale.a();
    //Act_sale.b();
});

