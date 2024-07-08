<?php

namespace App\Form;

use App\Entity\Feedback;
use App\Entity\Resume;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isPositive')
            ->add('recipient')
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Sent At',
            ])
            ->add('resume', EntityType::class, [
                'class' => Resume::class,
                'choice_label' => 'jobTitle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
        ]);
    }
}
