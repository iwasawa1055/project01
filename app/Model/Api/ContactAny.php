<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');

class ContactAny extends ApiModel
{
    public function __construct()
    {
        parent::__construct('ContactAny', '/contact_any');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
        (new InfoBox())->deleteCache();
        (new InfoItem())->deleteCache();
    }

    public function apiPostIsolateIsland($outbound)
    {
        $res = $this->apiPost([
            'title' => '（沖縄及び離島）出庫のお申込みを承りました',
            'text' => $this->createTextOutobountIsolateIsland($outbound),
            'contact_cd' => CONTACTUS_CD_ISOLATEISLANDS,
        ]);
    }

    private function createTextOutobountIsolateIsland($outbound)
    {
        $date = date('Y/m/d');
        $name = $outbound['lastname'] . $outbound['firstname'];
        $postal = $outbound['postal'];
        $address = $outbound['pref'] . $outbound['address1'] . $outbound['address2'] . $outbound['address3'];
        $tel = $outbound['tel1'];
        $content = $outbound['aircontent'];

        $products = explode(',', $outbound['product']);
        $product = $this->createTextProduct($products);

        $text = <<< EOF
（沖縄及び離島）出庫のお申込みを承りました。

お申込みの内容は以下の通りとなります。

申込内容
**************************************************
申込日：$date
出庫方法：宅配便
お届け希望日：指定不可
お届け先氏名：$name
お届け先住所：$postal $address
日中連絡先：$tel
内容：
$content

$product
**************************************************

出庫点数が複数ある場合は梱包をおまとめいたします。
出庫依頼品により複数梱包になった場合は、梱包数及び送料を追ってご連絡いたします。
＊個品出庫ならびにお預け入れ期間が1年未満の箱の場合、1梱包につき出庫料として800円を頂戴いたします。
EOF;

        return $text;
    }

    private function createTextProduct($products = [])
    {
        $num = 0;
        $prod = [];
        $name = '';
        $id = '';
        $text = '';

        foreach ($products as $key => $value) {
            $num = $key + 1;
            $prod = explode(':', $value);
            $name = PRODUCT_NAME[$prod[0]];
            $id = (count($prod) > 2) ? $prod[2] : $prod[1];

            $p = <<< PRODUCT
商品$num
　商品名：$name
　ボックス/アイテムNo：$id

PRODUCT;

            $text .= $p;
        }

        return $text;
    }


    public $validate = [
        'title' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'title']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 50],
                'message' => ['maxLength', 'title', 50]
            ],
        ],
        'text' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'text']
            ],
        ],
        'contact_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'contact_cd']
            ],
        ],
    ];
}
