<?php

/**
 * GaPageView base store for table: ga_page_view

 */

namespace Octo\GoogleAnalytics\Store\Base;

use Block8\Database\Connection;
use Octo\Store;
use Octo\GoogleAnalytics\Model\GaPageView;
use Octo\GoogleAnalytics\Model\GaPageViewCollection;
use Octo\GoogleAnalytics\Store\GaPageViewStore;

/**
 * GaPageView Base Store
 */
class GaPageViewStoreBase extends Store
{
    /** @var GaPageViewStore $instance */
    protected static $instance = null;

    /** @var string */
    protected $table = 'ga_page_view';

    /** @var string */
    protected $model = 'Octo\GoogleAnalytics\Model\GaPageView';

    /** @var string */
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return GaPageViewStore
     */
    public static function load() : GaPageViewStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new GaPageViewStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return GaPageView|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a GaPageView object by Id.
     * @param $value
     * @return GaPageView|null
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
     * Get all GaPageView objects by Metric.
     * @return \Octo\GoogleAnalytics\Model\GaPageViewCollection
     */
    public function getByMetric($value, $limit = null)
    {
        return $this->where('metric', $value)->get($limit);
    }

    /**
     * Gets the total number of GaPageView by Metric value.
     * @return int
     */
    public function getTotalByMetric($value) : int
    {
        return $this->where('metric', $value)->count();
    }
}
