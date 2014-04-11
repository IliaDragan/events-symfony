<?php

namespace IPG\EventsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IPG\EventsBundle\Controller\LocationController;

class LocationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Init location controller.
        $location = new LocationController;
        // Get field attributes that is the same as for autocomplete js.
        $fieldAttributes = $location->getGmapAttributes();

        $builder
            ->add('location', 'text', array(
                'mapped' => false,
                'attr' => $fieldAttributes['InputAttributes']
            ))
        ->add('save', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IPG\EventsBundle\Entity\Location'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ipg_eventsbundle_location';
    }
}
