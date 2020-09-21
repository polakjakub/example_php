<?php

namespace Driver;

class FileCacheDriver implements CacheDriver
{
    const CACHE_METHOD_PLAIN = 'plain';
    const CACHE_METHOD_JSON = 'json';
    
    /**
     * @param        $slug
     * @param int    $ttl
     * @param string $method
     * @return bool|mixed|string
     */
    public function getCache($slug, $ttl = 86400, $method = 'plain')
    {
        switch ($method) {
            case self::CACHE_METHOD_PLAIN:
                $cacheResult = $this->getCacheContents($slug, $ttl);
                break;
            case self::CACHE_METHOD_JSON:
                $cacheResult = @json_decode($this->getCacheContents($slug, $ttl));
                break;
        }
        
        return $cacheResult;
    }
    
    /**
     * @param        $slug
     * @param        $contents
     * @param string $method
     */
    public function saveCache($slug, $contents, $method = 'plain')
    {
        switch ($method) {
            case self::CACHE_METHOD_PLAIN:
                break;
            case self::CACHE_METHOD_JSON:
                $contents = json_encode($contents);
                break;
        }
        file_put_contents($this->getCacheFilename($slug), $contents);
    }
    
    /**
     * @param $slug
     * @param $ttl
     * @return bool|string
     */
    protected function getCacheContents($slug, $ttl)
    {
        $result = false;
        $filename = $this->getCacheFilename($slug);
        if (filemtime($filename) + $ttl >= time()) {
            $result = file_get_contents($filename);
        }
        
        return $result;
    }
    
    /**
     * @param $slug
     * @return string
     */
    protected function getCacheFilename($slug)
    {
        $filename = md5($slug); // some more logic like CACHE_DIR...
        
        return $filename;
    }
    
}