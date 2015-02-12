<?php

namespace Octo\GoogleAnalytics\Event;

use b8\Config;
use Octo\Admin\Template;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\System\Model\Setting;

class DashboardWidget extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('DashboardWidgets', array($this, 'getWidget'));
    }

    public function getWidget(&$widgets)
    {
        /** @var \Octo\AssetManager $assets */
        $assets = Config::getInstance()->get('Octo.AssetManager');
        $assets->addJs('GoogleAnalytics', 'analytics');

        if (Setting::get('analytics', 'ga_email') != '') {
            $view = Template::getAdminTemplate('Dashboard/widget', 'GoogleAnalytics');
            $widgets[] = ['order' => 0, 'html' => $view->render()];
        }
    }
}
