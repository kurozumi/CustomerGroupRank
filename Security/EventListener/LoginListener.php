<?php

namespace Plugin\CustomerGroupRank\Security\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Plugin\CustomerGroupRank\Service\Rank\Context;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(Context $context, EntityManagerInterface $entityManager)
    {
        $this->context = $context;
        $this->entityManager = $entityManager;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (!$user instanceof Customer) {
            return;
        }

        $this->context->decide($user);
        $this->entityManager->flush();
    }
}
