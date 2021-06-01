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

namespace Plugin\CustomerGroupRank\Service\Rank;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Plugin\CustomerGroup\Entity\Group;

class Rank implements RankInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * 優先度が最上位のグループを会員に設定する
     *
     * @param Customer $customer
     */
    public function decide(Customer $customer): bool
    {
        $groups = $this->getGroups($customer);
        if ($groups->count() > 0) {
            /** @var Group $group */
            $group = $groups->first();
            if ($customer->getGroups()->count() > 0) {
                foreach ($customer->getGroups() as $originGroup) {
                    $customer->removeGroup($originGroup);
                }
            }
            $customer->addGroup($group);
            $group->addCustomer($customer);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    /**
     * 会員に適用可能なグループ一覧を取得
     *
     * @param Customer $customer
     * @return array
     */
    protected function getGroups(Customer $customer): array
    {
        $searchData = [
            'buyTimes' => $customer->getBuyTimes(),
            'buyTotal' => $customer->getBuyTimes()
        ];
        $groups = $this->entityManager->getRepository(Group::class)->getQueryBuilderBySearchData($searchData)
            ->getQuery()
            ->getResult();

        return new ArrayCollection($groups);
    }
}
