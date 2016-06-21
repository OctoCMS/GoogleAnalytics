<?php

/**
 * GaTopPage base store for table: ga_top_page

 */

namespace Octo\GoogleAnalytics\Store\Base;

use Octo\Store;
use Octo\GoogleAnalytics\Model\GaTopPage;
use Octo\GoogleAnalytics\Model\GaTopPageCollection;

/**
 * GaTopPage Base Store
 */
class GaTopPageStoreBase extends Store
{
    protected $table = 'ga_top_page';
    protected $model = 'Octo\GoogleAnalytics\Model\GaTopPage';
    protected $key = 'id';

    /**
    * @param $value
    * @return GaTopPage|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a GaTopPage object by Id.
     * @param $value
     * @return GaTopPage|null
     */
    public function getById(int $value)
    {
        // This is the primary key, so try and get from cache:
        $cacheResult = $this->cacheGet($value);

        if (!empty($cacheResult)) {
            return $cacheResult;
        }

        $rtn = $this->where('id', $value)->first();
        $this->cacheSet($value, $rtn);

        return $rtn;
    }

    /**
     * Get a GaTopPage object by Uri.
     * @param $value
     * @return GaTopPage|null
     */
    public function getByUri(string $value)
    {
        return $this->where('uri', $value)->first();
    }

    /**
     * Get all GaTopPage objects by PageId.
     * @return \Octo\GoogleAnalytics\Model\GaTopPageCollection
     */
    public function getByPageId($value, $limit = null)
    {
        return $this->where('page_id', $value)->get($limit);
    }

    /**
     * Gets the total number of GaTopPage by PageId value.
     * @return int
     */
    public function getTotalByPageId($value) : int
    {
        return $this->where('page_id', $value)->count();
    }
}
