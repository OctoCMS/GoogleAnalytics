<?php

namespace Octo\GoogleAnalytics\Event;

use Octo\Event\Listener;
use Octo\Event\Manager;

class Jobs extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('RegisterJobHandlers', function (&$handlers) {
            $handlers[] = 'Octo\GoogleAnalytics\JobHandler\UpdateAnalyticsHandler';
        });

        $manager->registerListener('Job.Schedule', function (&$handlers) {
            $handlers['Octo.GoogleAnalytics.Update'] = [
                'frequency' => 86400,
            ];
        });
    }
}
