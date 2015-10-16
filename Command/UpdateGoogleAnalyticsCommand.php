<?php

namespace Octo\GoogleAnalytics\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use DateTime;
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


    protected function configure()
    {
        $this
            ->setName('analytics:update')
            ->setDescription('Update and cache Google Analytics.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        unset($input, $output);


    }


}
