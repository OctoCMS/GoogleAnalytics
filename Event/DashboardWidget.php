<?php

namespace Octo\GoogleAnalytics\Event;

use b8\Config;
use Octo\Template;
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
        $assets->addThirdParty('js', 'GoogleAnalytics', 'flot/jquery.flot.min.js');
        $assets->addThirdParty('js', 'GoogleAnalytics', 'flot/jquery.flot.resize.min.js');
        $assets->addThirdParty('js', 'GoogleAnalytics', 'flot/jquery.flot.pie.min.js');
        $assets->addJs('GoogleAnalytics', 'analytics');

        if (Setting::get('analytics', 'ga_profile_id') != '') {
            $template = new Template('GoogleAnalytics/widget', 'admin');
            $widgets[] = ['order' => 1, 'html' => $template->render()];
        }
    }
}
