<?php

namespace IPG\EventsBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use IPG\EventsBundle\Form\EventType;
use IPG\EventsBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EventController
 * @package IPG\EventsBundle\Controller
 *
 * @Route("/event")
 */
class EventController extends Controller
{
    /**
     * @Route("/create", name="event_create")
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
     * @Route("/show/{id}", name="event_page")
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

    /**
     * @Route("/", name="event_list")
     *
     * @Template()
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('IPGEventsBundle:Event');
        $events = $repo->findAll();

        return array(
            'events' => $events,
        );
    }

    /**
     * @param $id integer event id
     *
     * @Route("/edit/{id}", name="event_edit")
     *
     * @Template("IPGEventsBundle:Event:create.html.twig")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('IPGEventsBundle:Event')->find($id);

        if (!$event) {
            throw new $this->createNotFoundException('Event with id '.$id.' wasn\'t found! :(');
        }

        $form = $this->createForm(new EventType(), $event);
        $form->handleRequest($request);

        $originalCategories = new ArrayCollection();

        foreach ($event->getCategories() as $category) {
            $originalCategories->add($category);
        }

        if ($form->isValid()) {
            foreach ($originalCategories as $category) {
                if (false === $event->getCategories()->contains($category)) {
                    $category->getEvent()->removeElement($event);

                    $em->persist($category);
                }
            }
            $em->persist($event);
            $em->flush();

            return $this->redirect($this->generateUrl('event_page', array('id' => $event->getId())));
        }

        return array('form' => $form->createView());
    }
}
