<?php
/**
 * This file is part of CustomerGroupRank42
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$container->registerForAutoconfiguration(\Plugin\CustomerGroupRank42\Service\Rank\RankInterface::class)
    ->addTag(\Plugin\CustomerGroupRank42\DependencyInjection\Compiler\RankPass::TAG);

$container->addCompilerPass(new \Plugin\CustomerGroupRank42\DependencyInjection\Compiler\RankPass());
