<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class DatetimeDeliveryOutboundV4 extends ApiModel
{
    public function __construct()
    {
        parent::__construct('DatetimeDeliveryOutboundV4', '/outbound_delivery_datetime', 'minikura_v4');
    }

    public $validate = [
        'postal' => [
            'rule' => '/^\d{3}\-\d{4}$/i',
            'required' => true,
        ],
    ];

    private $time_code = [
        '2' => '午前中',
        '3' => '12～14時',
        '4' => '14～16時',
        '5' => '16～18時',
        '6' => '18～20時',
        '7' => '19～21時',
    ];

    public function apiGetDatetime($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }

        $data['oem_key'] = $this->oem_key;
        $d = $this->request($this->end_point, $data, 'GET');

        if ($d->status === '1') {
            foreach ($d->results as $key => $value) {
                $cd = explode('-', $value['datetime_cd']);
                if (count($cd) === 4) {
                    if (!empty($cd[3]) && array_key_exists($cd[3], $this->time_code)) {
                        $time = $this->time_code[$cd[3]];
                        $value['text'] = "{$cd[0]}/{$cd[1]}/{$cd[2]} (".$this->_getWeek($cd[0].'-'.$cd[1].'-'.$cd[2]).") {$time}";
                        // $value['text'] = "{$cd[0]}/{$cd[1]}/{$cd[2]} {$time}";
                    } else {
                        $value['text'] = "{$cd[0]}/{$cd[1]}/{$cd[2]} (".$this->_getWeek($cd[0].'-'.$cd[1].'-'.$cd[2]).")";
                    }
                } else if (count($cd) === 3) {
                    $value['text'] = "{$cd[0]}/{$cd[1]}/{$cd[2]} (".$this->_getWeek($cd[0].'-'.$cd[1].'-'.$cd[2]).")";
                }
                $res[] = $value;
            }

            $d->results = $res;
        }

        return $d;
    }

    private function _getWeek($date) {
      $week = [
        '日', //0
        '月', //1
        '火', //2
        '水', //3
        '木', //4
        '金', //5
        '土', //6
      ];

      return $week[date('w', strtotime($date))];
    }
}
