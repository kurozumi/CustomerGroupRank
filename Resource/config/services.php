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

$container->registerForAutoconfiguration(\Plugin\CustomerGroupRank\Service\Rank\RankInterface::class)
    ->addTag(\Plugin\CustomerGroupRank\DependencyInjection\Compiler\RankPass::TAG);

$container->addCompilerPass(new \Plugin\CustomerGroupRank\DependencyInjection\Compiler\RankPass());
