                    <div class="panel-footer">
                      <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                          <p class="box-list-caption"><span>商品ステータス</span><?php echo($master_sales_status_array[$sales_history['sales_status']]);?></p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                          <p class="box-list-caption"><span>販売日</span><?php echo($this->Time->format($sales_history['sales_start'], '%Y-%m-%d'));?></p>
                        </div>
                      </div>
                    </div>
