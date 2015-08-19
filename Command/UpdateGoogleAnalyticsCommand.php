<?php

namespace Octo\GoogleAnalytics\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use DateTime;
use SimpleXMLElement;

use Octo\GoogleAnalytics\Model\GaPageView;
use Octo\GoogleAnalytics\Model\GaSummaryView;
use Octo\GoogleAnalytics\Model\GaTopPage;
use Octo\Store;
use Octo\System\Model\Setting;

/**
 * Update stored Analytics information, pulled from GA API.
 */
class UpdateGoogleAnalyticsCommand extends Command
{
    protected $today;
    protected $monthAgo;
    protected $tableId;

    protected $client;
    protected $service;

    protected function configure()
    {
        $this
            ->setName('analytics:update')
            ->setDescription('Update and cache Google Analytics.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        unset($input, $output);

        $this->tableId = Setting::get('analytics', 'ga_profile_id');
        $this->today = (new DateTime())->format('Y-m-d');
        $this->monthAgo = (new DateTime())->modify('-1 month')->format('Y-m-d');

        $this->client = new \Google_Client();
        $this->client->setAccessToken(Setting::get('google-identity', 'access_token'));
        $this->service = new \Google_Service_Analytics($this->client);

        $this->getDateViews();
        $this->getResponsiveStats();
        $this->getTopPages();
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

            $store->saveByReplace($record);
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

        foreach ($response['rows'] as $row) {
            $record = new GaTopPage();
            $record->setUri($row[0]);
            $record->setUniquePageviews($row[1]);
            $record->setPageviews($row[2]);
            $record->setUpdated(new DateTime());
            $store->saveByReplace($record);
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
            $store->saveByReplace($record);

            $record = new GaPageView();
            $record->setUpdated(new DateTime());
            $record->setDate(new \DateTime($row[0]));
            $record->setMetric('visitors');
            $record->setValue($row[2]);
            $store->saveByReplace($record);

            $record = new GaPageView();
            $record->setUpdated(new DateTime());
            $record->setDate(new \DateTime($row[0]));
            $record->setMetric('pageviews');
            $record->setValue($row[3]);
            $store->saveByReplace($record);

            $record = new GaPageView();
            $record->setUpdated(new DateTime());
            $record->setDate(new \DateTime($row[0]));
            $record->setMetric('uniquePageviews');
            $record->setValue($row[4]);
            $store->saveByReplace($record);
        }
    }
}
