<?php

namespace IPG\EventsBundle\Form;

use IPG\EventsBundle\Form\DataTransformer\CategoryNameToCategoryTreansformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $options['em'];
        $transformer = new CategoryNameToCategoryTreansformer($entityManager);
        $builder
            ->add(
                $builder->create('category', 'text')->addModelTransformer($transformer)
            )
//            ->add('lft')
//            ->add('lvl')
//            ->add('rgt')
//            ->add('root')
            ->add('parent')
//            ->add('events')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(
                array('data_class' => 'IPG\EventsBundle\Entity\Category')
            )
            ->setRequired(array('em'))
            ->setAllowedTypes(
                array('em' => 'Doctrine\Common\Persistence\ObjectManager')
            );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'category';
    }
}
