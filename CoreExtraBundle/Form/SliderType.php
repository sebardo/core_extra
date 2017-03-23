<?php

namespace CoreExtraBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use CoreBundle\Form\ImageType;

/**
 * Class SliderType
 */
class SliderType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'A2lix\TranslationFormBundle\Form\Type\TranslationsType')
            ->add('openInNewWindow', null, array('required' => false))
            ->add('url', UrlType::class, array('required' => false))
            ->add('active', null, array('required' => false))
            ->add('image', ImageType::class, array(
                'required' => false
            ))
            ->add('removeImage', HiddenType::class, array( 'attr' => array(
                'class' => 'remove-image'
            )))
            ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>  'CoreExtraBundle\Entity\Slider',
        ));
    }
}
