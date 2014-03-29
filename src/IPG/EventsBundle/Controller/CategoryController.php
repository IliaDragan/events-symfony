<?php

namespace IPG\EventsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IPG\EventsBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/category", name="categories_index")
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository('IPGEventsBundle:Category')->findAll();

        return $this->render(
            'IPGEventsBundle:Category:index.html.twig',
            array('categories' => $categories)
        );
    }

    /**
     * @Route("/category/auto-complete/{name}", name="category_auto_complete")
     */
    public function autoCompleteAction($name)
    {
        $categories = $this->getDoctrine()
            ->getRepository('IPGEventsBundle:Category')
            ->findBy(
                array('categoryName' => $name)
            );

        return $this->render(
            'IPGEventsBundle:Category:index.html.twig',
            array('categories' => $categories)
        );
    }

    /**
     * @Route("/category/create/{name}", name="categories_create")
     */
    public function createAction($name)
    {
        $category = new Category();
        $category->setCategoryName($name);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return new Response('New category name: '.$category->getCategoryName());
    }
}
