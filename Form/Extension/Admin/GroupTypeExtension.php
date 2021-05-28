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

namespace Plugin\CustomerGroupRank\Form\Extension\Admin;


use Plugin\CustomerGroup\Form\Type\Admin\GroupType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('buyTimes', NumberType::class, [
                'label' => '購入回数',
                'eccube_form_options' => [
                    'auto_render' => true
                ]

            ])
            ->add('buyTotal', NumberType::class, [
                'label' => '購入金額',
                'eccube_form_options' => [
                    'auto_render' => true
                ]
            ]);
    }

    /**
     * @return string
     */
    public function getExtendedType(): string
    {
        return GroupType::class;
    }

    /**
     * @return iterable
     */
    public static function getExtendedTypes(): iterable
    {
        yield GroupType::class;
    }
}
