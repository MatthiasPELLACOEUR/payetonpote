<?php

namespace App\Form;

// use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('participant', ParticipantType::class)
        ->add('is_anonymous', CheckboxType::class, [
            'label'     => 'Être Anonyme ?',
            'required'  => false,
        ])
        ->add('amount')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}