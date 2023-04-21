<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,['attr'=>['class'=>'field', 'placeholder'=>"Nom de famille"]])
            ->add('prenoms', TextType::class,['attr'=>['class'=>'field', 'placeholder'=>"Prenoms"]])
            ->add('telephone', TextType::class,['attr'=>['class'=>'field', 'placeholder'=>"Numéro de telephone"]])
            ->add('club', TextType::class,['attr'=>['class'=>'field', 'placeholder'=>"Club"]])
            ->add('fonction', TextType::class,['attr'=>['class'=>'field', 'placeholder'=>"Fonction au Rotary"]])
            //->add('code')
            //->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
