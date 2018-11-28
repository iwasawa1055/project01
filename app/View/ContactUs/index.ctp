    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fa fa-pencil-square-o"></i> お問い合わせ</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2>お問い合わせ一覧</h2>
                            <a href="/contact_us/add" class="btn-contact">新規お問い合わせ作成</a>
                            <div class="col-lg-12">
                                <?php if ($ticket_list['count'] !== 0):?>
                                <div class="col-lg-12 announcement">
                                    <div class="row list">
                                        <div class="col-xs-12 col-md-2 col-lg-2">
                                            作成日
                                        </div>
                                        <div class="col-xs-12 col-md-3 col-lg-2">
                                            お問い合わせID
                                        </div>
                                        <div class="col-xs-12 col-md-5 col-lg-6">
                                            お問い合わせの種別
                                        </div>
                                        <div class="col-xs-12 col-md-2 col-lg-2">
                                            ステータス
                                        </div>
                                    </div>
                                    <?php foreach ($ticket_list['tickets'] as $data): ?>
                                    <div class="row list">
                                        <div class="col-xs-12 col-md-2 col-lg-2">
                                            <?php echo $this->Time->format($data['created_at'], '%Y年%m月%d日'); ?>
                                        </div>
                                        <div class="col-xs-12 col-md-3 col-lg-2">
                                            <?php echo h($data['id']);?>
                                        </div>
                                        <div class="col-xs-12 col-md-5 col-lg-6">
                                            <span class="detail"><a href="/contact_us/detail?ticket_id=<?php echo h($data['id'])?>"><?php echo h($data['subject']);?></a></span>
                                        </div>
                                        <div class="col-xs-12 col-md-2 col-lg-2 pull-right">
                                            <?php if ($data['status'] === 'solved'):?>
                                            <a class="btn btn-success btn-xs" href="/contact_us/detail?ticket_id=<?php echo h($data['id'])?>">完了</a>
                                            <?php else:?>
                                            <a class="btn btn-danger btn-xs" href="/contact_us/detail?ticket_id=<?php echo h($data['id'])?>">オープン</a>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                    <?php endforeach;?>
                                    <?php echo $this->element('paginator'); ?>
                                    <?php else:?>
                                </div>
                                <p class="form-control-static col-lg-12">ただ今、お問い合わせしている情報はございません。</ br></p>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
