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

namespace Plugin\CustomerGroupRank\Security\EventSubscriber;


use Eccube\Entity\Customer;
use Plugin\CustomerGroupRank\Service\GroupDecisionService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LoginSubscriber implements EventSubscriberInterface
{
    /**
     * @var GroupDecisionService
     */
    protected $groupDecisionService;

    public function __construct(GroupDecisionService $groupDecisionService)
    {
        $this->groupDecisionService = $groupDecisionService;
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin'
        ];
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (!$user instanceof Customer) {
            return;
        }

        $this->groupDecisionService->decide($user);
    }
}
