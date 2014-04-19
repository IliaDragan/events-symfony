<?php

namespace IPG\EventsBundle\Controller;

use IPG\EventsBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IPG\EventsBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
            ->findLike(
                array('categoryName', $name)
            );

        return $this->render(
            'IPGEventsBundle:Category:index.html.twig',
            array('categories' => $categories)
        );
    }

    /**
     * @Route("/category/create/", name="categories_create")
     */
    public function createAction(Request $request)
    {

        $category = new Category();

        $form = $this->createForm(new CategoryType(), $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }
        return $this->render('IPGEventsBundle:Category:create.html.twig',
            array('form' => $form->createView()));
    }
}
