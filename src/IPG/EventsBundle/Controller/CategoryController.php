<?php

namespace IPG\EventsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
}
