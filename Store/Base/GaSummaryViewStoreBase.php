<?php

/**
 * GaSummaryView base store for table: ga_summary_view

 */

namespace Octo\GoogleAnalytics\Store\Base;

use Block8\Database\Connection;
use Octo\Store;
use Octo\GoogleAnalytics\Model\GaSummaryView;
use Octo\GoogleAnalytics\Model\GaSummaryViewCollection;
use Octo\GoogleAnalytics\Store\GaSummaryViewStore;

/**
 * GaSummaryView Base Store
 */
class GaSummaryViewStoreBase extends Store
{
    /** @var GaSummaryViewStore $instance */
    protected static $instance = null;

    /** @var string */
    protected $table = 'ga_summary_view';

    /** @var string */
    protected $model = 'Octo\GoogleAnalytics\Model\GaSummaryView';

    /** @var string */
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return GaSummaryViewStore
     */
    public static function load() : GaSummaryViewStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new GaSummaryViewStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return GaSummaryView|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a GaSummaryView object by Id.
     * @param $value
     * @return GaSummaryView|null
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
     * Get a GaSummaryView object by Metric.
     * @param $value
     * @return GaSummaryView|null
     */
    public function getByMetric(string $value)
    {
        return $this->where('metric', $value)->first();
    }
}
