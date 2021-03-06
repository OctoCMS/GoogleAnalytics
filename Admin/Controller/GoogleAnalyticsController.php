<?php

namespace Octo\GoogleAnalytics\Admin\Controller;

use \DateTime;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\Store;
use Octo\GoogleAnalytics\Model\GaPageView;
use Octo\GoogleAnalytics\Store\GaPageViewStore;

class GoogleAnalyticsController extends Controller
{
    /**
     * @var \Octo\GoogleAnalytics\Store\GaTopPageStore
     */
    protected $gaTopPageStore;

    /**
     * @var \Octo\GoogleAnalytics\Store\GaPageViewStore
     */
    protected $gaPageViewStore;

    /**
     * @var \Octo\GoogleAnalytics\Store\GaSummaryViewStore
     */
    protected $gaSummaryViewStore;

    public static function registerMenus(Menu $menu)
    {
        $root = $menu->addRoot('Google Analytics', '/google-analytics', true)->setIcon('chart');
        $root->addChild(new Menu\Item('Metric', '/google-analytics/metric'));
        $root->addChild(new Menu\Item('Responsive', '/google-analytics/responsive'));
        $root->addChild(new Menu\Item('Top Pages', '/google-analytics/top-pages'));
        $root->addChild(new Menu\Item('Responsive', '/google-analytics/top-unique-pages'));
    }

    public function init()
    {
        $this->gaTopPageStore = Store::get('GaTopPage');
        $this->gaPageViewStore = Store::get('GaPageView');
        $this->gaSummaryViewStore = Store::get('GaSummaryView');
    }

    public function responsive()
    {
        $data = $this->gaSummaryViewStore->getResponsiveMetrics();

        $return = [];
        $total = 0;

        foreach ($data as $item) {
            $return[$item->getMetric()] = ['percentage' => 0, 'count' => $item->getValue()];
            $total += $item->getValue();
        }

        foreach ($return as &$item) {
            $item['percentage'] = number_format(($item['count'] / $total) * 100, 1);
        }

        print json_encode($return);
        exit;
    }

    public function topPages()
    {
        $pages = $this->gaTopPageStore->getPages(5, 'pageviews');

        $data = [];
        $maxValue = $this->gaPageViewStore->getLastMonthTotal('pageviews');

        foreach ($pages as $item) {
            $page = $item->getPage();
            if (isset($page)) {
                $pageName = $page->getCurrentVersion()->getShortTitle();
            } else {
                $pageName = $item->getUri();
            }

            $data[] = [
                'percentage' => number_format((100 / $maxValue) * $item->getPageviews(), 1),
                'metric' => $item->getPageviews(),
                'uri' => $item->getUri(),
                'name' => $pageName,
            ];
        }

        print json_encode($data);
        exit;
    }

    public function topUniquePages()
    {
        $pages = $this->gaTopPageStore->getPages(5, 'unique_pageviews');

        $data = [];
        $maxValue = $this->gaPageViewStore->getLastMonthTotal('uniquePageviews');

        foreach ($pages as $item) {
            $page = $item->getPage();
            if (isset($page)) {
                $pageName = $page->getCurrentVersion()->getShortTitle();
            } else {
                $pageName = ltrim($item->getUri(), '/');
            }

            $data[] = [
                'percentage' => number_format((100 / $maxValue) * $item->getUniquePageviews(), 1),
                'metric' => $item->getUniquePageviews(),
                'uri' => $item->getUri(),
                'name' => $pageName,
            ];
        }

        print json_encode($data);
        exit;
    }

    public function metric($metric)
    {
        $start = $this->getParam('start_date');
        $end = $this->getParam('end_date');

        if ($end == null) {
            $end = new DateTime();
            $end = $end->format('Y-m-d');
        }
        if ($start == null) {
            $start = new DateTime();
            $start = $start->modify('-30 days')->format('Y-m-d');
        }

        $store = Store::get('GaPageView');
        $data = $store->getMetricBetween($metric, $start, $end);

        $ticks = [];
        $tickCount = 0;
        $maxValue = 0;
        $return = [];
        foreach ($data as $day) {
            $value = $day->getValue();
            $return[] = ['date' => $day->getDate()->format('d-m-Y'), 'value' => $value];

            // Reset maximum value
            if ($value > $maxValue) {
                $maxValue = $value;
            }

            // Add every third day to the ticks
            if ($tickCount % 3 == 0) {
                $ticks[$tickCount] = $day->getDate()->format('d/m');
            }
            $tickCount++;
        }

        $maxValue = $this->cleverRound($maxValue);

        array_unshift($return, ['max' => $maxValue, 'ticks' => $ticks]);

        print json_encode($return);
        exit;
    }

    protected function cleverRound($maxValue)
    {
        $digits = strlen($maxValue);
        $base = intval(substr($maxValue, 0, 1)) + 1;
        $maxValue = $base . str_repeat('0', $digits - 1);
        return $maxValue;
    }
}
