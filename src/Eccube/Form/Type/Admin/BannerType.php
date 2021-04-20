<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Form\Type\Admin;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\Banner;
use Eccube\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Eccube\Form\Type\ToggleSwitchType;
use Eccube\Repository\CategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;

class BannerType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Image File',
                'required' => false,
                'mapped' => false,
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_mtext_len']]),
                ],
            ])
            ->add('url', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_mtext_len']]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'トップページバナー' => '1',
                    'ヘッダーSPバナー' => '2',
                ],
            ])
            ->add('device', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    'PC' => '1',
                    'SP' => '2',
                ],
            ])
            ->add('status', ToggleSwitchType::class)
            ->add('sort', IntegerType::class, [
                'label' => '表示順',
                'required' => false,
                'constraints' => [

                    new Regex([
                        'pattern' => "/^\d+$/u",
                        'message' => 'form_error.numeric_only',
                    ]),
                    new Length(['max' => 11]),
                ],
                'eccube_form_options' => [
                    'auto_render' => true,
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Banner::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_banner';
    }
}
