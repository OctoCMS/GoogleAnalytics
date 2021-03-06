<?php

/**
 * GaSummaryView model collection
 */

namespace Octo\GoogleAnalytics\Model;

use Block8\Database\Model\Collection;

/**
 * GaSummaryView Model Collection
 */
class GaSummaryViewCollection extends Collection
{
    /**
     * Add a GaSummaryView model to the collection.
     * @param string $key
     * @param GaSummaryView $value
     * @return GaSummaryViewCollection
     */
    public function addGaSummaryView($key, GaSummaryView $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return GaSummaryView|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
