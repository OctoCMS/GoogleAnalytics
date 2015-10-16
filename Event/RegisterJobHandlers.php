<?php

namespace Octo\GoogleAnalytics\Event;

use Octo\Event\Listener;
use Octo\Event\Manager;

class RegisterJobHandlers extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('RegisterJobHandlers', function (&$handlers) {
            $handlers[] = 'Octo\GoogleAnalytics\JobHandler\UpdateAnalyticsHandler';
        });
    }
}
