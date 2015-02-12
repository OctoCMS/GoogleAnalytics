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
    protected $authHeader;

    protected $today;

    protected $monthAgo;

    protected $tableId;

    protected function configure()
    {
        $this
            ->setName('analytics:update')
            ->setDescription('Update and cache Google Analytics.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        unset($input, $output);

        $email = Setting::get('analytics', 'ga_email');
        $password = Setting::get('analytics', 'ga_password');
        $this->tableId = Setting::get('analytics', 'ga_profile_id');

        $this->today = (new DateTime())->format('Y-m-d');
        $this->monthAgo = (new DateTime())->modify('-1 month')->format('Y-m-d');
        $this->authHeader = $this->getAuthHeader($email, $password);

        $this->getDateViews();
        $this->getResponsiveStats();
        $this->getTopPages();
    }

    protected function getResponsiveStats()
    {
        $vars = [
            'ids' => $this->tableId,
            'start-date' => $this->monthAgo,
            'end-date' => $this->today,
            'metrics' => 'ga:uniquePageviews',
            'sort' => '-ga:uniquePageViews',
            'dimensions' => 'ga:deviceCategory',
            'max-results' => 10,
            'prettyprint' => 1
        ];

        $xml = $this->getFeed($vars, $this->authHeader);
        foreach ($xml->entry as $entry) {
            $entry->registerXPathNamespace('dxp', 'http://schemas.google.com/analytics/2009');

            $metric = str_replace('ga:deviceCategory=', '', $entry->title[0]);
            $value = (int) $entry->xpath('dxp:metric')[0]->attributes()->value;

            $record = new GaSummaryView();
            $record->setMetric($metric);
            $record->setValue($value);
            $record->setUpdated(new DateTime());

            $summaryStore = Store::get('GaSummaryView');
            $summaryStore->saveByReplace($record);
        }
    }

    protected function getTopPages()
    {
        $vars = [
            'ids' => $this->tableId,
            'start-date' => $this->monthAgo,
            'end-date' => $this->today,
            'metrics' => 'ga:uniquePageViews,ga:pageViews',
            'sort' => '-ga:uniquePageViews',
            'dimensions' => 'ga:pagePath',
            'max-results' => 10,
            'prettyprint' => 1
        ];

        $xml = $this->getFeed($vars, $this->authHeader);
        foreach ($xml->entry as $entry) {
            $entry->registerXPathNamespace('dxp', 'http://schemas.google.com/analytics/2009');

            $pageUri = str_replace('ga:pagePath=', '', $entry->title[0]);
            $uniquePageViews = (int) $entry->xpath('dxp:metric')[0]->attributes()->value;
            $pageViews = (int) $entry->xpath('dxp:metric')[1]->attributes()->value;

            $record = new GaTopPage();
            $record->setUri($pageUri);
            $record->setUniquePageviews($uniquePageViews);
            $record->setPageviews($pageViews);
            $record->setUpdated(new DateTime());

            $pageStore = Store::get('Page');
            $page = $pageStore->getByUri($pageUri);

            if ($page) {
                $record->setPageId($page->getId());
            }

            $topPageStore = Store::get('GaTopPage');
            $topPageStore->saveByReplace($record);
        }
    }

    protected function getDateViews()
    {
        $vars = [
            'ids' => $this->tableId,
            'start-date' => $this->monthAgo,
            'end-date' => $this->today,
            'metrics' => 'ga:visits,ga:visitors,ga:pageviews,ga:uniquePageviews',
            'sort' => 'ga:visits',
            'dimensions' => 'ga:date',
            'max-results' => 1000,
            'prettyprint' => 1
        ];

        $xml = $this->getFeed($vars, $this->authHeader);

        foreach ($xml->entry as $entry) {
            $entry->registerXPathNamespace('dxp', 'http://schemas.google.com/analytics/2009');
            $date = DateTime::createFromFormat('Ymd', $entry->xpath('dxp:dimension')[0]->attributes()->value);
            $metrics = $entry->xpath('dxp:metric');
            foreach ($metrics as $metric) {
                $name = str_replace('ga:', '', $metric->attributes()->name[0]);
                $value = (int) $metric->attributes()->value[0];

                $record = new GaPageView();
                $record->setDate($date);
                $record->setMetric($name);
                $record->setValue($value);
                $record->setUpdated(new DateTime());

                $pageViewStore = Store::get('GaPageView');
                $pageViewStore->saveByReplace($record);
            }
        }
    }

    protected function getAuthHeader($email, $password)
    {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, "https://www.google.com/accounts/ClientLogin");
        $authData = [
            'Email' => $email,
            'Passwd' => $password,
            'accountType' => 'GOOGLE',
            'source' => 'curl-dataFeed-v2',
            'service' => 'analytics'
        ];
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $authData);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($handle);
        curl_close($handle);

        preg_match('/Auth\=(.*)/', $result, $matches);
        $authHeader = $matches[1];
        return $authHeader;
    }

    protected function getFeed($vars, $authHeader)
    {
        $feedUri = 'https://www.google.com/analytics/feeds/data?';
        foreach ($vars as $k => $v) {
            $feedUri .= $k . '=' . $v . '&';
        }
        $feedUri = rtrim($feedUri, '&');
        $headers = [
            "Authorization: GoogleLogin auth=$authHeader",
            "GData-Version: 2"
        ];

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $feedUri);
        curl_setopt($handle, CURLOPT_HEADER, false);
        curl_setopt($handle, CURLINFO_HEADER_OUT, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($handle);
        curl_close($handle);

        $xml = new SimpleXMLElement($result);
        return $xml;
    }
}
