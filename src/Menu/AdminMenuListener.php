<?php

namespace App\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $newSubmenu = $menu
            ->addChild('new')
            ->setLabel('Newsletter');

        $newSubmenu
            ->addChild('new-subitem', ['route' => 'admin_newsletter'])
            ->setLabel('Newsletters');
    }
}
