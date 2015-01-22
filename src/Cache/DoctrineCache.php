<?php
/**
 * Created by Two Developers - Sven Motz und Jens Averkamp GbR
 * http://www.two-developers.com
 *
 * Developer: Jens Averkamp
 * Date: 22.01.2015
 * Time: 13:40
 */

namespace WebsiteInfo\Cache;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FlushableCache;

class DoctrineCache implements CacheInterface {

    /** @var Cache */
    private $cache;

    /**
     * @param Cache $cache
     */
    function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }


    /**
     * Fetches an entry from the cache.
     *
     * @param string $id The id of the cache entry to fetch.
     *
     * @return mixed The cached data or FALSE, if no cache entry exists for the given id.
     */
    public function fetch($id)
    {
        return $this->cache->fetch($id);
    }

    /**
     * Tests if an entry exists in the cache.
     *
     * @param string $id The cache id of the entry to check for.
     *
     * @return boolean TRUE if a cache entry exists for the given cache id, FALSE otherwise.
     */
    public function contains($id)
    {
        return $this->cache->contains($id);
    }

    /**
     * Puts data into the cache.
     *
     * @param string $id The cache id.
     * @param mixed $data The cache entry/data.
     * @param int $lifeTime The cache lifetime.
     *                         If != 0, sets a specific lifetime for this cache entry (0 => infinite lifeTime).
     *
     * @return boolean TRUE if the entry was successfully stored in the cache, FALSE otherwise.
     */
    public function save($id, $data, $lifeTime = 0)
    {
        return $this->cache->save($id, $data, $lifeTime);
    }

    /**
     * Deletes a cache entry.
     *
     * @param string $id The cache id.
     *
     * @return boolean TRUE if the cache entry was successfully deleted, FALSE otherwise.
     */
    public function delete($id)
    {
        return $this->cache->delete($id);
    }

    /**
     * Flushes all cache entries.
     *
     * @return boolean TRUE if the cache entries were successfully flushed, FALSE otherwise.
     */
    public function flushAll()
    {
        if($this->cache instanceof FlushableCache) {
            return $this->cache->flushAll();
        }

        return false;
    }


}
