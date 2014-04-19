<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 4/19/14
 * Time: 10:48 PM
 */

namespace IPG\EventsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('categoryName', 'text');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IPG\EventsBundle\Entity\Category'
        ));
    }

    public function getName()
    {
        return 'ipg_category';
    }
}
