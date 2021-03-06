<?php

/**
 * GaTopPage store for table: ga_top_page
 */

namespace Octo\GoogleAnalytics\Store;

use b8\Database;
use Octo;
use Octo\GoogleAnalytics\Model\GaTopPage;

/**
 * GaTopPage Store
 * @uses Octo\GoogleAnalytics\Store\Base\GaTopPageStoreBase
 */
class GaTopPageStore extends Base\GaTopPageStoreBase
{
	public function getPages($limit = 5, $order = 'pageviews')
    {
        $query = "SELECT * FROM ga_top_page ORDER BY $order DESC LIMIT $limit";
        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new GaTopPage($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }
}
