<?php

/**
 * GaSummaryView base store for table: ga_summary_view

 */

namespace Octo\GoogleAnalytics\Store\Base;

use Octo\Store;
use Octo\GoogleAnalytics\Model\GaSummaryView;
use Octo\GoogleAnalytics\Model\GaSummaryViewCollection;

/**
 * GaSummaryView Base Store
 */
class GaSummaryViewStoreBase extends Store
{
    protected $table = 'ga_summary_view';
    protected $model = 'Octo\GoogleAnalytics\Model\GaSummaryView';
    protected $key = 'id';

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
