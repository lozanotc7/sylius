<?php

declare(strict_types=1);

namespace App\Form\Extension;

use Sylius\Bundle\CoreBundle\Form\Type\Customer\CustomerRegistrationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class MyCustomerRegistrationTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('SubscribedToNewsletter')
            ->add('newsletters', EntityType::class, array(
                'class' => 'App\Entity\Newsletter\Newsletter',
                'expanded' => true,
                'multiple' => true ))
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [CustomerRegistrationType::class];
    }
}
