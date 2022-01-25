<?php

declare(strict_types=1);

namespace App\Form\Extension;

use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\CoreBundle\Form\Type\Customer\CustomerRegistrationType;
use Sylius\Bundle\CustomerBundle\Form\Type\CustomerProfileType;
use Sylius\Bundle\ResourceBundle\Form\Type\DefaultResourceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class NewsletterTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('subscribers');

        if($this->areEditing($options)) {
            $this->addSubscribers($builder, $this->getDataId($options));
        }
    }

    public static function getExtendedTypes(): iterable
    {
        return [DefaultResourceType::class];
    }

    private function addSubscribers(FormBuilderInterface $builder, int $id)
    {
        $builder
            ->add('subscribers', EntityType::class, array(
                'class' => 'App\Entity\Customer\Customer',
                'query_builder' => function(EntityRepository $er) use($id) {
                    return $er->createQueryBuilder('c')
                        ->join('c.newsletters', 'n')
                        ->add('orderBy', 'c.firstName ASC')
                        ->where('n.id = :newsletterId')
                        ->setParameter('newsletterId', $id);
                },
                'expanded' => true,
                'multiple' => true,
                'disabled' => true));
    }

    private function areEditing(array $options): bool
    {
        $id = $this->getDataId($options);

        return is_int($id);
    }

    private function getDataId(array $options)
    {
        return $options['data']->getId();
    }
}
