<?php


/**
 *
 */
class ApiCachedModel extends ApiModel
{
    const SESSION_BASE_KEY = 'ApiCachedModel';
    private $sessionKey = '';
    private $lifetime = 300;

    public function __construct($sessionKey, $name, $end, $access_point_key = 'minikura_v3', $lifetime = 300)
    {
        $this->sessionKey = ApiCachedModel::SESSION_BASE_KEY . '.' . $sessionKey;
        $this->lifetime = $lifetime;
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

    public function apiGet($data = [])
    {
        $data = parent::apiGet($data);
        $this->deleteCache();
        return $data;
    }
    public function apiPost($data)
    {
        $data = parent::apiPost($data);
        $this->deleteCache();
        return $data;
    }
    public function apiPut($data)
    {
        $data = parent::apiPut($data);
        $this->deleteCache();
        return $data;
    }
    public function apiPatch($data)
    {
        $data = parent::apiPatch($data);
        $this->deleteCache();
        return $data;
    }
    public function apiDelete($data)
    {
        $data = parent::apiDelete($data);
        $this->deleteCache();
        return $data;
    }

    protected function readCache($key, $arg)
    {
        $sessionKey = $this->sessionKey . '.' . $key;
        $session = CakeSession::read($sessionKey);
        if (!empty($session) && !empty(Hash::get($session, 'expires')) && time() < $session['expires'] &&
                (Hash::get($session, 'arg') === $arg)) {

            // if ($this->getModelName() == 'Announcement') {
            //     pr($this->getModelName() . ' cached xx ' . date('H:i:s', $session['expires']) . ' ... ' . date('H:i:s'));
            // }
            return Hash::get($session, 'data');
        }
        return null;
    }
    protected function writeCache($key, $arg, $data)
    {
        // if ($this->getModelName() == 'Announcement') {
        //     pr($this->getModelName() . ' writeCache ... ' . date('H:i:s'));
        // }
        $sessionKey = $this->sessionKey . '.' . $key;
        CakeSession::write($sessionKey, [
            'arg' => $arg,
            'expires' => time() + $this->lifetime,
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
