<?php

namespace IPG\EventsBundle\Controller;

use IPG\EventsBundle\Form\EventType;
use IPG\EventsBundle\Form\LocationType;
use IPG\EventsBundle\Entity\Event;
use IPG\EventsBundle\Entity\Location;
use IPG\EventsBundle\Controller\LocationController;
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
        // Init event object.
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);
        $form->handleRequest($this->get('request_stack')->getCurrentRequest());

        // Init location object.
        $locationEntity = new Location();
        // $form_entity = $this->createForm(new LocationType(), $locationEntity);
        $LocationController = new LocationController();

        $request = $this->container->get('request');
        if ($request->getMethod() == 'POST')
        {
            if ($form->isValid())
            {
                $address = $_POST['ipg_eventsbundle_event']['location'];
                $locationEntity->setAddress($address);

                // Init geoceder.
                $geocoder = $LocationController->initGeocoder();

                // Get lat. && long. from provided address.
                try {
                    $geocode = $geocoder->geocode($address);
                    $locationEntity->setLatitude($geocode->getLatitude());
                    $locationEntity->setLongitude($geocode->getLongitude());
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

                // Define Doctrine.
                $em = $this->getDoctrine()->getManager();
                // Save Location and return location id to be set in event object.
                $locationId = $this->getDoctrine()
                    ->getRepository('IPGEventsBundle:Location')
                    ->getId(array('address', $address));

                if (!$locationId)
                {
                    $em->persist($locationEntity);
                    $em->flush();

                    $locationId = $locationEntity->getId();
                } else {
                    $locationId = $locationId[0]['id'];
                }

                // Finally save event object.
                $event->setLocationId($locationId);
                $em->persist($event);
                $em->flush();

                return $this->redirect($this->generateUrl('event_page', array('id' => $event->getId())));
            }
        }

        $gmapAutocomplete = new LocationController;
        return $this->render('IPGEventsBundle:Location:create.html.twig',
            array(
                'form' => $form->createView(),
                'map' => $gmapAutocomplete->mapAutocomplete()
            ));
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
