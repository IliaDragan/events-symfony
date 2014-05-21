<?php

namespace IPG\EventsBundle\Form;

use IPG\EventsBundle\Entity\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locationId')
            ->add('name')
            ->add('description')
            ->add('parentCategory',
                'entity',
                array(
                    'class' => 'IPGEventsBundle:Category',
                    'property' => 'categoryName',
                    'query_builder' => function(CategoryRepository $er) {
                            return $er->createQueryBuilder('c')
                                ->where('c.parent is NULL');
                        },
                    'mapped' => false,
                )
            )
            ->add('categories', 'collection', array(
                'type'          => new CategoryType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
            ))
            ->add('pictures', new PictureType(), array('mapped' => false))
            ->add('save', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IPG\EventsBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ipg_eventsbundle_event';
    }
}
