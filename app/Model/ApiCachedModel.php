<?php

App::uses('ApiModel', 'Model');

/**
 *
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

    public function apiGetResultsFind($data = [], $where = [])
    {
        return $this->apiGetResultsWhere($data, $where, true);
    }

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

    public function apiGet($data = []) {
        new AppInternalCritical('donot call apiGet() in ApiCachedModel', 500);
    }

    protected function triggerDataChanged() {
        $this->deleteCache();
    }

    protected function readCache($key, $arg)
    {
        $sessionKey = $this->sessionKey . '.' . $key;
        $session = CakeSession::read($sessionKey);
        if (!empty($session) && (Hash::get($session, 'arg') === $arg)) {
            $expires = Hash::get($session, 'expires');
            if (empty($expires) || time() <= $expires) {
                return Hash::get($session, 'data');
            }
        }
        return null;
    }
    protected function writeCache($key, $arg, $data)
    {
        $sessionKey = $this->sessionKey . '.' . $key;
        $expires = 0;
        if (!empty($this->lifetime) && 0 < $this->lifetime) {
            $expires = time() + $this->lifetime;
        }
        CakeSession::write($sessionKey, [
            'arg' => $arg,
            'expires' => $expires,
            'data' => $data
        ]);
    }
    public function deleteCache()
    {
        CakeSession::delete($this->sessionKey);
    }

    public static function deleteAllCache()
    {
        CakeSession::delete(ApiCachedModel::SESSION_BASE_KEY);
    }

    private function apiGetListWithCache($arg = [])
    {
        // TODO: 引数からキャッシュキーを作る
        $key = 'apiGet';
        $list = $this->readCache($key, $arg);
        if (!empty($list)) {
            return $list;
        }

        // すべて取得
        $list = [];
        $offset = 0;
        $count = 0;
        $limit = 1000;
        do {
            $newArg = $arg;
            $newArg['offset'] = $offset;
            $newArg['limit'] = $limit;
            $r = parent::apiGet($newArg);
            if (!$r->isSuccess()) {
                return $r;
            }
            $addList = $r->results;
            $count = count($addList);
            $list = array_merge($list, $addList);
            $offset++;
        } while ($limit === $count);
        $this->writeCache($key, $arg, $list);
        // 期限を設定
        return $list;
    }
}
