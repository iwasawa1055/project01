<?php

App::uses('ApiModel', 'Model');

class InboundAndOutboundHistory extends ApiModel
{
    public function __construct()
    {
        parent::__construct('InboundAndOutboundHistory', '/inbound_and_outbound_history', 'minikura_v5');
    }

    public function searchTerm($results, $params)
    {

        if (empty($params['keyword'])) {
            // sort
            $sort_key = ['create_date' => false];
            HashSorter::sort($results, $sort_key);
            return $results;
        }

        $keywords_tmp = explode('OR', $params['keyword']);

        $keywords = null;
        foreach ($keywords_tmp as $k => $v) {
            $and_words = explode(' ', str_replace('　', ' ',$v));
            $and_words = array_filter($and_words);
            $and_words = array_values($and_words);

            $keywords[] = $and_words;
        }

        $all_minus_flag = true;
        foreach ($keywords as $and_lists) {
            foreach ($and_lists as $and_word) {
                if (strpos($and_word, '-') !== 0) {
                    $all_minus_flag = false;
                }
            }
        }

        // ランク付け用にポイントをそれぞれ設定
        $columns = [
            'delivery_name' => 100,
            'box_ids' => 90,
            'item_ids' => 80,
            'delivery_state' => 50,
            'delivery_city' => 40,
            'delivery_street_address' => 30,
            'delivery_suburb' => 20,
        ];

        // 検索
        $hits = AppSearch::makeRank($results, $keywords, $columns, $all_minus_flag);

        // sort
        $sort_key = ['create_date' => false];
        HashSorter::sort($hits, $sort_key);

        return $hits;
    }
}
