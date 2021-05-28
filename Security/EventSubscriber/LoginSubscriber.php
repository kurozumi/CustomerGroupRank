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


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Plugin\CustomerGroup\Entity\Group;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LoginSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
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

        $searchData = [
            'buyTimes' => $user->getBuyTimes(),
            'buyTotal' => $user->getBuyTimes()
        ];
        $groups = $this->entityManager->getRepository(Group::class)->getQueryBuilderBySearchData($searchData)
            ->getQuery()
            ->getResult();
        $groups = new ArrayCollection($groups);

        if ($groups->count() > 0) {
            /** @var Group $group */
            $group = $groups->first();
            if ($user->getGroups()->count() > 0) {
                foreach($user->getGroups() as $originGroup) {
                    $user->removeGroup($originGroup);
                }
            }
            $user->addGroup($group);
            $group->addCustomer($user);
            $this->entityManager->flush();
        }
    }
}
