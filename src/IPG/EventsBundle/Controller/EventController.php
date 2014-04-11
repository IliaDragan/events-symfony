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
        // Init location entity.
        $locationEntity = new Location();
        // Init location controller.
        $locationController = new LocationController();

        $request = $this->container->get('request');
        if ($request->getMethod() == 'POST')
        {
            if ($form->isValid())
            {
                // Get location address from &_POST.
                $address = $_POST['ipg_eventsbundle_event']['location'];
                // Try to retrive location id from the db based on given address.
                $locationId = $this->getDoctrine()
                    ->getRepository('IPGEventsBundle:Location')
                    ->getId(array('address', $address));
                // Define Doctrine.
                $em = $this->getDoctrine()->getManager();
                // Check if already exist location with given address if not create new one.
                if (!$locationId)
                {
                    // Set address in Location entity.
                    $locationEntity->setAddress($address);
                    // Init geoceder.
                    $geocoder = $locationController->initGeocoder();
                    // Get latitude && longitude from provided address.
                    try {
                        // Get geo data based on given address.
                        $geocode = $geocoder->geocode($address);
                        // Set latitude in Location entity.
                        $locationEntity->setLatitude($geocode->getLatitude());
                        // Set longitude in Location entity.
                        $locationEntity->setLongitude($geocode->getLongitude());
                    } catch (Exception $e) {
                        // Echo the error.
                        echo $e->getMessage();
                    }
                    // Create new Location entity.
                    $em->persist($locationEntity);
                    $em->flush();
                     // Get new location id
                    $locationId = $locationEntity->getId();
                } else {
                    // Use already created location.
                    $locationId = $locationId[0]['id'];
                }
                // Finally save event object.
                $event->setLocationId($locationId);
                $em->persist($event);
                $em->flush();
                // Redirect user to overview page.
                return $this->redirect($this->generateUrl('event_page', array('id' => $event->getId())));
            }
        }
        // Render create Event entity form.
        return $this->render('IPGEventsBundle:Location:create.html.twig',
            array(
                'form' => $form->createView(),
                'map' => $locationController->mapAutocomplete()
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
