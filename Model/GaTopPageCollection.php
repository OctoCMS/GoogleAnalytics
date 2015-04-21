<?php

/**
 * GaTopPage model collection
 */

namespace Octo\GoogleAnalytics\Model;

use Octo;
use b8\Model\Collection;

/**
 * GaTopPage Model Collection
 */
class GaTopPageCollection extends Collection
{
    /**
     * Add a GaTopPage model to the collection.
     * @param string $key
     * @param GaTopPage $value
     * @return GaTopPageCollection
     */
    public function add($key, GaTopPage $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return GaTopPage|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
