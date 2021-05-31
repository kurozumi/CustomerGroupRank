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

namespace Plugin\CustomerGroupRank\DependencyInjection\Compiler;


use Plugin\CustomerGroupRank\Service\Rank\Context;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RankPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    const TAG = 'plugin.customer.group.rank';

    public function process(ContainerBuilder $container)
    {
        $context = $container->findDefinition(Context::class);

        foreach ($this->findAndSortTaggedServices(self::TAG, $container) as $id) {
            $context->addMethodCall('addRank', [new Reference($id)]);
        }
    }
}
