<?php
/**
 * This file is part of CustomerGroupRank
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\CustomerGroupRank;


use Eccube\Event\TemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Event implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            '@CustomerGroup/admin/Customer/Group/edit.twig' => 'onTemplateAdminCustomerGroupEdit',
        ];
    }

    public function onTemplateAdminCustomerGroupEdit(TemplateEvent $event): void
    {
        $event->addSnippet('@CustomerGroupRank/admin/Customer/Group/edit.twig');
    }
}
