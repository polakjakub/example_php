<?php

namespace Driver;

interface CacheDriver
{
    public function getCache($slug, $ttl = 86400, $method = 'plain');
    public function saveCache($slug, $contents, $method = 'plain');
}