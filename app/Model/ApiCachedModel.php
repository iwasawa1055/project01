<?php


/**
 *
 */
class ApiCachedModel extends ApiModel
{
    const SESSION_BASE_KEY = 'ApiCachedModel';
    private $sessionKey = '';

    public function __construct($sessionKey, $name, $end, $access_point_key = 'minikura_v3')
    {
        $this->sessionKey = ApiCachedModel::SESSION_BASE_KEY . '.' . $sessionKey;
        parent::__construct($name, $end, $access_point_key);
    }

    public function apiGetResults($arg = [])
    {
        $list = $this->apiGetResultsWithCache();
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

    protected function readCache($key)
    {
        $sessionKey = $this->sessionKey . '.' . $key;
        return CakeSession::read($sessionKey);
    }
    protected function writeCache($key, $data)
    {
        $sessionKey = $this->sessionKey . '.' . $key;
        CakeSession::write($sessionKey, $data);
    }
    public function deleteCache()
    {
        CakeSession::delete($this->sessionKey);
    }

    public static function deleteAllCache()
    {
        CakeSession::delete(ApiCachedModel::SESSION_BASE_KEY);
    }

    private function apiGetResultsWithCache($arg = [])
    {
        $key = 'apiGet';
        $list = $this->readCache($key);
        if (!empty($list)) {
            return $list;
        }

        // すべて取得
        $list = [];
        $offset = 0;
        $count = 0;
        $limit = 1000;
        do {
            $arg['offset'] = $offset;
            $arg['limit'] = $limit;
            $addList = parent::apiGetResults($arg);;
            $count = count($addList);
            $list = array_merge($list, $addList);
            $offset++;
        } while ($limit === $count);
        $this->writeCache($key, $list);
        return $list;
    }
}
