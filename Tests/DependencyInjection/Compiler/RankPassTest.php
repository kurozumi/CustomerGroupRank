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

namespace Plugin\CustomerGroupRank\Tests\DependencyInjection\Compiler;


use Eccube\Entity\Customer;
use PHPUnit\Framework\TestCase;
use Plugin\CustomerGroupRank\DependencyInjection\Compiler\RankPass;
use Plugin\CustomerGroupRank\Service\Rank\Context;
use Plugin\CustomerGroupRank\Service\Rank\RankInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RankPassTest extends TestCase
{
    public function testTestRankが追加されるか()
    {
        $container = new ContainerBuilder();
        $container->register(Context::class)
            ->setPublic(true);

        $container->register(TestRank::class)
            ->addTag(RankPass::TAG);

        $container->addCompilerPass(new RankPass());
        $container->compile();

        $context = $container->get(Context::class);
        $reflection = new \ReflectionObject($context);
        $prop = $reflection->getProperty('ranks');
        $prop->setAccessible(true);
        $ranks = $prop->getValue($context);

        self::assertCount(1, $ranks);
    }
}

class TestRank implements RankInterface
{
    public function decide(Customer $customer): void
    {
    }
}
