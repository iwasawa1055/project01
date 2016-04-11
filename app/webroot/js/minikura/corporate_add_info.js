  function clicked(sender){
    if(sender == 'card'){
      document.getElementsByName('data[CorporateRegistInfo][payment_method]')[0].checked = true;
    }else if(sender == 'account'){
      document.getElementsByName('data[CorporateRegistInfo][payment_method]')[1].checked = true;
    };
  }
