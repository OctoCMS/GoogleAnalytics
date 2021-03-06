<?php

/**
 * GaSummaryView store for table: ga_summary_view
 */

namespace Octo\GoogleAnalytics\Store;

use b8\Database;
use Octo;
use Octo\GoogleAnalytics\Model\GaSummaryView;

/**
 * GaSummaryView Store
 * @uses Octo\GoogleAnalytics\Store\Base\GaSummaryViewStoreBase
 */
class GaSummaryViewStore extends Base\GaSummaryViewStoreBase
{
	public function getResponsiveMetrics()
    {
        $query = "SELECT * FROM ga_summary_view
        WHERE metric IN('desktop', 'tablet', 'mobile')";
        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new GaSummaryView($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }
}
