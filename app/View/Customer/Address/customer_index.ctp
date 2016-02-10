   <div class="row">
     <div class="col-lg-12">
       <h1 class="page-header"><i class="fa fa-truck"></i> 住所・お届け先変更</h1>
     </div>
   </div>
   <div class="row">
     <div class="col-lg-12">
       <div class="panel panel-default">
         <div class="panel-body">
           <div class="row">
             <div class="col-lg-12">
               <?php if (0 < count($address)) : ?>
               <h2>お届け先変更</h2>
               <div class="form-group col-lg-12">
                 <form>
                   <select class="form-control" onchange="$('.address_id').val($(this).val());">
                     <option>以下からお選びください</option>
                     <?php foreach ($address as $data): ?>
                       <option value="<?php echo $data['address_id']; ?>">
                         <?php echo "〒${data['postal']} ${data['pref']}${data['address1']}${data['address2']}${data['address3']}　${data['lastname']}${data['firstname']}"; ?>
                       </option>
                     <?php endforeach; ?>;
                   </select>
                 </form>
               </div>
               <?php echo $this->Form->create('CustomerAddress', ['url' => ['controller' => 'address', 'action' => 'delete', 'step' => 'confirm']]); ?>
               <span class="col-lg-6 col-md-6 col-xs-12">
                   <input type="hidden" class="address_id" name="address_id" value="">
                   <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">削除する</button>
               </span>
               <?php echo $this->Form->end(); ?>
               <?php echo $this->Form->create('CustomerAddress', ['type' => 'get', 'url' => ['controller' => 'address', 'action' => 'edit', 'step' => 'confirm']]); ?>
               <span class="col-lg-6 col-md-6 col-xs-12">
                   <input type="hidden" class="address_id" name="address_id" value="">
                   <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">変更する</button>
               </span>
               <?php echo $this->Form->end(); ?>
             <?php endif ?>
               <span class="col-lg-12 col-md-12 col-xs-12">
               <a class="btn btn-danger btn-lg btn-block animsition-link" href="/customer/address/add"> 新規追加する </a>
               </span>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>