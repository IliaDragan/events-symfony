<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 4/11/14
 * Time: 9:08 PM
 */

namespace IPG\EventsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use IPG\EventsBundle\Entity\Category;

class CategoryNameToCategoryTreansformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transform object Category to string
     *
     * @param Category|null $category
     * @return string
     */
    public function transform($category)
    {
        if (null === $category)
        {
            return "";
        }

        return $category->getCategoryName();
    }

    public function reverseTransform($categoryName)
    {
        if (!$categoryName) {
            return null;
        }

        $category = $this->om
            ->getRepository('IPGEventsBundle:Category')
            ->findOneBy(array('categoryName' => $categoryName));

        // TODO: Create new category if not found
//        if (null === $category) {
//
//            throw new TransformationFailedException(
//                sprintf('An category with name "%s" does not exist!', $categoryName)
//            );
//        }

        return $category;
    }
}
