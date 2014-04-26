<?php

namespace IPG\EventsBundle\Controller;

use IPG\EventsBundle\Form\EventType;
use IPG\EventsBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class EventController extends Controller
{
    /**
     * @Route("/event/create")
     *
     * @Template()
     */
    public function createAction() {
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);
        $form->handleRequest($this->get('request_stack')->getCurrentRequest());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            if ($categories = $event->getCategories()) {
                $parentCategory = $form->get('parentCategory')->getData();
                foreach ($categories as $category) {
                    $category->setParent($parentCategory);
                    $em->persist($category);
                }
            }
            $em->flush();

            return $this->redirect($this->generateUrl('event_page', array('id' => $event->getId())));
        }


        return array('form' => $form->createView());
    }

    /**
     * @Route("/event/{id}", name="event_page")
     *
     * @Template()
     */
    public function indexAction($id) {

        $event = new Event();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('IPGEventsBundle:Event');
        $event = $repo->find($id);
        return array('event' => $event);
    }
}
