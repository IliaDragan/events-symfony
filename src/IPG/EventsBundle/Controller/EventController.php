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
     * @Template()
     */
    public function createAction() {
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);
        $form->handleRequest($this->get('request_stack')->getCurrentRequest());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirect($this->generateUrl('event_page'));
        }


        return array('form' => $form->createView());
    }
}