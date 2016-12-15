<?php

namespace Octo\GoogleAnalytics\Event;

use Octo\Event\Listener;
use Octo\Event\Manager;

class GoogleIdentityScope extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('Octo.GoogleIdentity.GetScopes', function (&$scopes) {
            $scopes[] = 'https://www.googleapis.com/auth/analytics.readonly';
        });
    }
}
