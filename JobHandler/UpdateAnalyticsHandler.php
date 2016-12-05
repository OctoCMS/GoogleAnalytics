<?php

namespace Octo\GoogleAnalytics\JobHandler;

use DateTime;

use Octo\Job\Handler;
use Octo\GoogleAnalytics\Model\GaPageView;
use Octo\GoogleAnalytics\Model\GaSummaryView;
use Octo\GoogleAnalytics\Model\GaTopPage;
use Octo\Store;
use Octo\System\Model\Setting;

class UpdateAnalyticsHandler extends Handler
{
    protected $today;
    protected $monthAgo;
    protected $tableId;
    protected $client;
    protected $service;

    public static function getJobTypes()
    {
        return [
            'Octo.GoogleAnalytics.Update' => 'Update Google Analytics',
        ];
    }

    public function run()
    {
        $this->tableId = Setting::get('analytics', 'ga_profile_id');
        $this->today = (new DateTime())->format('Y-m-d');
        $this->monthAgo = (new DateTime())->modify('-1 month')->format('Y-m-d');

        $this->client = new \Google_Client();
        $this->client->setClientId(Setting::get('google-identity', 'client_id'));
        $this->client->setClientSecret(Setting::get('google-identity', 'client_secret'));
        $this->client->setRedirectUri('postmessage');
        $this->client->setAccessToken(Setting::get('google-identity', 'access_token'));
        $this->service = new \Google_Service_Analytics($this->client);

        $this->getDateViews();
        $this->getResponsiveStats();
        $this->getTopPages();

        return true;
    }

    protected function getResponsiveStats()
    {
        $response = $this->service->data_ga->get($this->tableId, $this->monthAgo, $this->today, 'ga:uniquePageviews', [
            'sort' => '-ga:uniquePageViews',
            'dimensions' => 'ga:deviceCategory',
            'max-results' => 10,
        ]);

        $store = Store::get('GaSummaryView');

        foreach ($response['rows'] as $row) {
            $record = new GaSummaryView();
            $record->setMetric($row[0]); // e.g. "mobile"
            $record->setValue($row[1]); // e.g. 501
            $record->setUpdated(new DateTime());

            $store->replace($record);
        }
    }

    protected function getTopPages()
    {
        $response = $this->service->data_ga->get($this->tableId, $this->monthAgo, $this->today, 'ga:uniquePageViews,ga:pageViews', [
            'sort' => '-ga:uniquePageViews',
            'dimensions' => 'ga:pagePath',
            'max-results' => 10,
        ]);

        $store = Store::get('GaTopPage');
        $pageStore = Store::get('Page');

        foreach ($response['rows'] as $row) {
            $record = new GaTopPage();
            $record->setUri($row[0]);
            $record->setUniquePageviews($row[1]);
            $record->setPageviews($row[2]);
            $record->setUpdated(new DateTime());

            $page = $pageStore->getByUri($row[0]);

            if ($page) {
                $record->setPageId($page->getId());
            }

            $store->replace($record);
        }
    }

    protected function getDateViews()
    {
        $response = $this->service->data_ga->get($this->tableId, $this->monthAgo, $this->today, 'ga:visits,ga:visitors,ga:pageviews,ga:uniquePageviews', [
            'sort' => 'ga:visits',
            'dimensions' => 'ga:date',
            'max-results' => 1000,
        ]);

        $store = Store::get('GaPageView');

        foreach ($response['rows'] as $row) {
            $record = new GaPageView();
            $record->setUpdated(new DateTime());
            $record->setDate(new \DateTime($row[0]));
            $record->setMetric('visits');
            $record->setValue($row[1]);
            $store->replace($record);

            $record = new GaPageView();
            $record->setUpdated(new DateTime());
            $record->setDate(new \DateTime($row[0]));
            $record->setMetric('visitors');
            $record->setValue($row[2]);
            $store->replace($record);

            $record = new GaPageView();
            $record->setUpdated(new DateTime());
            $record->setDate(new \DateTime($row[0]));
            $record->setMetric('pageviews');
            $record->setValue($row[3]);
            $store->replace($record);

            $record = new GaPageView();
            $record->setUpdated(new DateTime());
            $record->setDate(new \DateTime($row[0]));
            $record->setMetric('uniquePageviews');
            $record->setValue($row[4]);
            $store->replace($record);
        }
    }
}