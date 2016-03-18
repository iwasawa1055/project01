<?php

App::uses('ApiModel', 'Model');

/**
 * API問い合わせモデルクラス
 * 結果をキャッシュする
 * 内容検索や並び替えを行います。
 */
class ApiCachedModel extends ApiModel
{
    const SESSION_BASE_KEY = 'ApiCachedModel';
    private $sessionKey = '';
    private $lifetime = 300;

    public function __construct($sessionKey, $lifetime, $name, $end, $access_point_key = 'minikura_v3')
    {
        $this->sessionKey = self::SESSION_BASE_KEY . '.' . $sessionKey;
        $this->lifetime = $lifetime * 1;
        parent::__construct($name, $end, $access_point_key);
    }

    /**
     * 条件に合う問い合わせ結果を1件取得
     * @param  array $data  パラメータ値
     * @param  array $where 条件
     * @return array       結果配列
     */
    public function apiGetResultsFind($data = [], $where = [])
    {
        return $this->apiGetResultsWhere($data, $where, true);
    }

    /**
     * 条件に合う問い合わせ結果
     * @param  array $data  パラメータ値
     * @param  array $where 条件
     * @param  boolean $firstOnly 1件のみか
     * @return array       結果配列
     */
    public function apiGetResultsWhere($data = [], $where = [], $firstOnly = false)
    {
        $keyList = array_keys($where);
        if (count($keyList) === 0) {
            return [];
        }
        $apiRes = $this->apiGetResults($data);
        if (!is_array($apiRes)) {
            return [];
        }
        $findList = [];
        foreach ($apiRes as $a) {
            $notMatch = false;
            foreach ($where as $key => $value) {
                if (!is_array($value)) {
                    $value = [$value];
                }
                if (!array_key_exists($key, $a) || !in_array($a[$key], $value, true)) {
                    $notMatch = true;
                    break;
                }
            }
            if (!$notMatch) {
                $findList[] = $a;
                if ($firstOnly) {
                    return $a;
                }
            }
        }
        return $findList;
    }

    /**
     * GETメソッドでAPI問い合わせ
     * @param  array $arg パラメータ値
     * @return array 結果配列
     */
    public function apiGetResults($arg = [])
    {

        $list = $this->apiGetListWithCache($arg);
        if (!is_array($list)) {
            return $list;
        }
        $offset = 0;
        if (!empty($arg['offset']) && is_int($arg['offset'])) {
            $offset = $arg['offset'];
        }
        $limit = 0;
        if (!empty($arg['limit']) && is_int($arg['limit'])) {
            $new = intval($arg['limit']);
            if (($offset + $new) < count($list)) {
                $limit = $new;
            } else {
                $limit = count($list) - $offset;
            }
        }
        if ($offset === 0 && $limit === 0) {
            return $list;
        }
        return array_slice($list, $offset, $limit);
    }

    /**
     * 通常の問い合わせを利用しない
     */
    public function apiGet($data = []) {
        new AppInternalCritical('donot call apiGet() in ApiCachedModel', 500);
    }

    /**
     * データ変更がある場合はキャッシュクリア
     */
    protected function triggerDataChanged() {
        $this->deleteCache();
    }
    /**
     * イベント：キャッシュ利用した
     */
    protected function triggerUsingCache() {
    }
    /**
     * イベント：キャッシュ利用しない
     */
    protected function triggerNotUsingCache() {
        CakeLog::write(DEBUG_LOG, 'NotUsingCache: ' . get_class($this));
    }
    /**
     * キャッシュ復元
     * キー：
     * $this->sessionKey . $key
     * データ：
     * [
     * 	"arg" => パラメータ値
     * 	"expires" => 有効期限
     * 	"data" => 問い合わせ結果
     * ]
     * @param  string $key 任意のキー
     * @param  array $arg パラメータ値
     * @return array      問い合わせ結果
     */
    protected function readCache($key, $arg)
    {
        $aryKey = $arg;
        unset($aryKey['offset']);
        unset($aryKey['limit']);
        ksort($aryKey);

        $sessionKey = $this->sessionKey . '.' . $key;
        $session = CakeSession::read($sessionKey);
        if (!empty($session) && (Hash::get($session, 'arg') === $aryKey)) {
            $expires = Hash::get($session, 'expires');
            if (empty($expires) || time() <= $expires) {
                return Hash::get($session, 'data');
            }
        }
        return null;
    }
    /**
     * キャッシュ保存
     * @param  string $key 任意のキー
     * @param  array $arg パラメータ値
     * @param  array $data 問い合わせ結果
     * @return null
     */
    protected function writeCache($key, $arg, $data)
    {
        $aryKey = $arg;
        unset($aryKey['offset']);
        unset($aryKey['limit']);
        ksort($aryKey);

        $sessionKey = $this->sessionKey . '.' . $key;
        $expires = 0;
        if (!empty($this->lifetime) && 0 < $this->lifetime) {
            $expires = time() + $this->lifetime;
        }
        CakeSession::write($sessionKey, [
            'arg' => $aryKey,
            'expires' => $expires,
            'data' => $data
        ]);
    }
    /**
     * 自モデルキャッシュ削除
     */
    public function deleteCache()
    {
        CakeSession::delete($this->sessionKey);
    }

    /* static */
    /**
     * 全キャッシュモデルキャッシュ削除
     */
    public static function deleteAllCache()
    {
        CakeSession::delete(ApiCachedModel::SESSION_BASE_KEY);
    }

    /* private */
    /**
     * API問い合わせを行いキャッシュデータを作成
     * @param  array $arg パラメータ値
     * @return array     問い合わせ結果
     */
    private function apiGetListWithCache($arg = [])
    {
        $key = 'apiGet';
        $list = $this->readCache($key, $arg);
        if (!empty($list)) {
            $this->triggerUsingCache();
            return $list;
        }
        $this->triggerNotUsingCache();

        // すべて取得
        $list = [];
        $offset = 0;
        $count = 0;
        $limit = 1000;
        do {
            $newArg = $arg;
            $newArg['offset'] = $offset;
            $newArg['limit'] = $limit;
            $apiRes = parent::apiGet($newArg);
            if (!$apiRes->isSuccess()) {
                // キャッシュ削除し例外発生
                $message = get_class($this) . ', call parent::apiGet(), result: ' . $apiRes->message;
                self::deleteCache();
                new AppInternalCritical($message, 500);
                return $apiRes;
            }
            $addList = $apiRes->results;
            $count = count($addList);
            $list = array_merge($list, $addList);
            $offset++;
        } while ($limit === $count);
        $this->writeCache($key, $arg, $list);
        // 期限を設定
        return $list;
    }
}
