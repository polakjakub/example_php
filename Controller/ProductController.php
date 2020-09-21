<?php

namespace Controller;

use Driver\CacheDriver;
use Driver\Factory\DatabaseDriverFactory;
use Driver\FileCacheDriver;
use Driver\IElasticSearchDriver;

class ProductController
{
    protected $cache_slug = 'product:%d';
    protected $cache_ttl = 86400;
    protected $cache_method = FileCacheDriver::CACHE_METHOD_PLAIN;
    protected $cacheDriver;
    protected $DBDriver;
    
    
    public function __construct(CacheDriver $cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;
        $this->DBDriver = DatabaseDriverFactory::create();
    }
    
    /**
     * @param string $id
     * @return string
     */
    public function detail($id)
    {
        $this->recordProductQuery($id);
        $productDetails = $this->cacheDriver->getCache($this->getCacheSlug($id), $this->cache_ttl, $this->cache_method);
        if (false === $productDetails) {
            switch ($this->DBDriver) {
                case IElasticSearchDriver::class:
                    $productDetails = $this->DBDriver->findById($id);
                    break;
                case IMySQLDriver::class:
                    $productDetails = $this->DBDriver->findProduct($id);
                    break;
            }
    
            $this->cacheDriver->saveCache($this->getCacheSlug($id), $productDetails);
        }
        
        return json_encode($productDetails);
    }
    
    /**
     * @param $id
     */
    protected function recordProductQuery($id)
    {
        $this->DBDriver->addOneToProduct($id); // tady chybi v zadani zpusob jak to ukladat, ale asi tam bude neco jako UPDATE `product` SET `queries` = `queries` + 1 WHERE id ...
    
    }
    
    /**
     * @param $id
     * @return string
     */
    protected function getCacheSlug($id)
    {
        return sprintf($this->cache_slug, $id);
    }
}