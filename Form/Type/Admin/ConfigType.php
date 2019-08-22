<?php

namespace Plugin\EC4MOMO\Form\Type\Admin;

use Plugin\EC4MOMO\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConfigType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('env', ChoiceType::class, [
            'choices' => [
                'TEST' => 'https://test-payment.momo.vn',
                'PRODUCTION' => 'https://payment.momo.vn',
            ],
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ])->add('partner_code', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 50]),
            ],
        ])->add('store_id', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 50]),
            ],
        ])->add('accessKey', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 50]),
            ],
        ])->add('secretKey', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 50]),
            ],
        ])->add('apiEndpoint', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
