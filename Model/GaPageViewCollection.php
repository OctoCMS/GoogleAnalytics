<?php

/**
 * GaPageView model collection
 */

namespace Octo\GoogleAnalytics\Model;

use Octo;
use b8\Model\Collection;

/**
 * GaPageView Model Collection
 */
class GaPageViewCollection extends Collection
{
    /**
     * Add a GaPageView model to the collection.
     * @param string $key
     * @param GaPageView $value
     * @return GaPageViewCollection
     */
    public function addGaPageView($key, GaPageView $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return GaPageView|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
