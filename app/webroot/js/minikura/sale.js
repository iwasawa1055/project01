var Act_sale = 
{
    /*
     * 販売履歴のselect by sales_status
     * */
    a : function(){
        $("select").change(function(event){
            var sales_status = "";
            var href = "/sale/index";
            $("select option:selected").each(function(){
                sales_status = $(this).val();
                console.log(sales_status);
                location.href = href + '?sales_status=' + sales_status;
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

