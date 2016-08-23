                    <?php $url = "/item/detail/{$sales_history['item_id']}"?>
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-2 col-xs-3 item-detail">
                          <img src="<?php echo h($sales_history['item_image'][0]['image_url']);?>" alt="">
                        </div>
                        <div class="col-lg-7 col-md-7 col-xs-9">
                          <h3 class="box-item-name"><?php echo h($sales_history['sales_title']);?></h3>
                          <p class="box-item-remarks"><?php echo h($sales_history['sales_note']);?></p>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12">
                          <a class="btn btn-danger btn-md btn-detail pull-right animsition-link" href="<?php echo $url;?>">アイテムを確認する</a>
                        </div>
                      </div>
                    </div>
