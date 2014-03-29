<?php

namespace IPG\EventsBundle\Controller;

use IPG\EventsBundle\Form\LocationType;
use IPG\EventsBundle\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ivory\GoogleMap\Places\Autocomplete;
use Ivory\GoogleMap\Places\AutocompleteComponentRestriction;
use Ivory\GoogleMap\Places\AutocompleteType;
use Ivory\GoogleMap\Helper\Places\AutocompleteHelper;

// require_once('/usr/share/php/FirePHPCore/fb.php');

class LocationController extends Controller
{
    /**
     * @Route("/location/create")
     *
     * @Template()
     */
    public function createAction() {
        $location = new Location();
        $form = $this->createForm(new LocationType(), $location);
        $form->handleRequest($this->get('request_stack')->getCurrentRequest());

        $this->mapAutocomplete();



        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($location);
            $em->flush();

            return $this->redirect($this->generateUrl('location_page', array('id' => $location->getId())));
        }

        return array('form' => $form->createView());
    }


    /**
     * @Route("/location/{id}", name="location_page")
     *
     * @Template()
     */
    public function indexAction($id) {
        $event = new Location();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('IPGEventsBundle:Location');
        $location = $repo->find($id);
        return array('location' => $location);
    }




    public function mapAutocomplete()
    {
        $autocomplete = new Autocomplete();

        $autocomplete->setPrefixJavascriptVariable('place_autocomplete_');
        $autocomplete->setInputId('place_input');

        $autocomplete->setInputAttributes(array('class' => 'my-class'));
        // $autocomplete->setInputAttribute('class', 'my-class');

        // $autocomplete->setValue('foo');

        $autocomplete->setTypes(array(AutocompleteType::ESTABLISHMENT));
        // $autocomplete->setComponentRestrictions(array(AutocompleteComponentRestriction::COUNTRY => 'fr'));
        // $autocomplete->setBound(-2.1, -3.9, 2.6, 1.4, true, true);

        $autocomplete->setAsync(false);
        $autocomplete->setLanguage('en');

        // Render our autocomplete field.
        $autocompleteHelper = new AutocompleteHelper();
        echo $autocompleteHelper->renderHtmlContainer($autocomplete);
        echo $autocompleteHelper->renderJavascripts($autocomplete);

        // fb($autocomplete);
    }
}
