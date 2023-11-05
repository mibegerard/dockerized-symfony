<?php

// src/Form/ExerciseConfirmationType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ExerciseConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('confirm', SubmitType::class, [
            'label' => 'Confirm Deletion',
            'attr' => ['class' => 'btn btn-danger'],
        ]);
    }
}
