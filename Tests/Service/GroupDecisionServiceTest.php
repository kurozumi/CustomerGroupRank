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

namespace Plugin\CustomerGroupRank\Tests\Service;


use Eccube\Entity\Customer;
use Eccube\Tests\EccubeTestCase;
use Plugin\CustomerGroup\Tests\TestCaseTrait;
use Plugin\CustomerGroupRank\Service\GroupDecisionService;

class GroupDecisionServiceTest extends EccubeTestCase
{
    use TestCaseTrait;

    /**
     * @var GroupDecisionService
     */
    protected $groupDecisionService;

    public function setUp()
    {
        parent::setUp();

        $this->groupDecisionService = $this->container->get(GroupDecisionService::class);
    }

    public function testDecide()
    {
        $group1 = $this->createGroup();
        $group1->setBuyTimes(1);
        $group1->setBuyTotal(1000);
        $group1->setSortNo(2);
        $group2 = $this->createGroup();
        $group2->setBuyTimes(2);
        $group2->setBuyTotal(2000);
        $group2->setSortNo(1);

        $customer = $this->createCustomer();
        $customer->setBuyTimes(2);
        $customer->setBuyTotal(2000);

        $this->entityManager->flush();

        $this->groupDecisionService->decide($customer);

        $groups = $this->entityManager->find(Customer::class, $customer->getId())->getGroups();

        self::assertEquals($group2, $groups->first());
    }
}
