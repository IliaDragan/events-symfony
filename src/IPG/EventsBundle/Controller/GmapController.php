<?php

namespace IPG\EventsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;

// FB Debug
// require_once('/usr/share/php/FirePHPCore/fb.php');

class GmapController extends Controller
{
    /**
     * @Route("/map")
     *
     * @var Ivory\GoogleMapBundle\Model\Map
     *
     * @see documentation https://github.com/egeloen/ivory-google-map
     */
    public function indexAction()
    {
        // Init map.
        $map = $this->get('ivory_google_map.map');

        $userLocation = $this->getUserLocation();

        // Set marker on the map.
        // $marker = $this->setMarker($latitude, $longitude);

        // Let's set some default settings.
        // More of them you can find in app\config\cconfig.yml.
        $map->setAutoZoom(false);
        $map->setCenter($userLocation['latitude'], $userLocation['longitude'], true);
        $map->setMapOption('zoom', 8);

        if (isset($marker))
        {
            $map->addMarker($marker);
        }

        // $map->setStylesheetOptions(array(
        //     'width'  => '100%',
        //     'height' => '100%',
        // ));

        return $this->render('IPGEventsBundle:Gmap:index.html.twig', array('map' => $map));
    }

    /**
     * @return user location data
     */
    public function getUserLocation()
    {
        // Get user Ip.
        $userAgentIp = $this->get('request')->getClientIp();

        // Get GeoLocation by Ip.
        $result = $this->container
            ->get('bazinga_geocoder.geocoder')
            ->geocode($userAgentIp);

        return array(
            'latitude'  => $result->getLatitude(),
            'longitude' => $result->getLongitude(),
        );
    }

    /**
     * @param $latitude
     *
     * @param $longitude
     *
     * @see documentation https://github.com/egeloen/ivory-google-map/blob/master/doc/usage/overlays/marker.md
     */
    public function setMarker($latitude, $longitude)
    {
        $marker = new Marker();

        // Configure your marker options
        $marker->setPrefixJavascriptVariable('marker_');
        $marker->setPosition($latitude, $longitude, true);
        $marker->setAnimation(Animation::DROP);

        $marker->setOptions(array(
            'clickable' => TRUE,
            'flat'      => true,
        ));
        return $marker;
    }
}
