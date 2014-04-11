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

require_once('/usr/share/php/FirePHPCore/fb.php');

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

        $request = $this->container->get('request');
        if ($request->getMethod() == 'POST')
        {
            if ($form->isValid())
            {
                $address = $_POST['ipg_eventsbundle_location']['location'];

                $location->setAddress($address);

                // Init geoceder.
                $geocoder = $this->initGeocoder();

                // Get lat. && long. from provided address.
                try {
                    $geocode = $geocoder->geocode($address);
                    $location->setLatitude($geocode->getLatitude());
                    $location->setLongitude($geocode->getLongitude());
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($location);
                $em->flush();

                return $this->redirect($this->generateUrl('location_page', array('id' => $location->getId())));
            }
        }

        return $this->render('IPGEventsBundle:Location:create.html.twig',
            array(
                'form' => $form->createView(),
                'map' => $this->mapAutocomplete()
            ));
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

    /**
     * Generate autocomplete object and return js needed for autocomplete.
     */
    public function mapAutocomplete()
    {
        $autocomplete = new Autocomplete();

        $fieldAttributes = $this->getGmapAttributes();

        $autocomplete->setPrefixJavascriptVariable('place_autocomplete_');
        $autocomplete->setInputId($fieldAttributes['InputId']);

        $autocomplete->setInputAttributes($fieldAttributes['InputAttributes']);

        // $autocomplete->setValue('foo');

        $autocomplete->setTypes(array(AutocompleteType::ESTABLISHMENT));
        // $autocomplete->setComponentRestrictions(array(AutocompleteComponentRestriction::COUNTRY => 'fr'));
        // $autocomplete->setBound(-2.1, -3.9, 2.6, 1.4, true, true);

        $autocomplete->setAsync(false);
        $autocomplete->setLanguage('en');


        // Render our autocomplete field.
        $autocompleteHelper = new AutocompleteHelper();

        // Prepare html for output.
        // $html = '';
        // $html .= '<div class="control-group">';
        // $html .= $autocompleteHelper->renderHtmlContainer($autocomplete);
        // $html .= '</div>';

        // Prepare js for output.
        $js = $autocompleteHelper->renderJavascripts($autocomplete);

        $output = /*$html .*/ $js;
        return $output;
    }

    /**
     * @return Gmap attributes needed for js and form field.
     */
    public function getGmapAttributes() {
        return array(
            // @note: For #id we use real field #id.
            // @todo: Find a way to set our own #id.
            'InputId' => 'ipg_eventsbundle_location_location',
            'InputAttributes' => array(
                'class'       => 'gmap-autocompleteplace',
                'type'        => 'text',
                'placeholder' => 'Type your location',
                'required'    => 'required',
                'autocomplete' => 'on',
            )
        );
    }

    /**
     * Init geocoder object.
     * @return object
     */
    public function initGeocoder() {
        $geocoder = new \Geocoder\Geocoder();
        $adapter  = new \Geocoder\HttpAdapter\CurlHttpAdapter();
        $chain    = new \Geocoder\Provider\ChainProvider(array(
            new \Geocoder\Provider\GoogleMapsProvider($adapter, 'en_EN', 'English', true),
        ));
        $geocoder->registerProvider($chain);
        return $geocoder;
    }
}
