<?php

namespace IPG\EventsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
        // get user Ip.
        $userAgentIp = $this->get('request')->getClientIp();

        // Get GeoLocation by Ip.
        $result = $this->container
            ->get('bazinga_geocoder.geocoder')
            ->geocode($userAgentIp);

        $latitude = $result->getLatitude();
        $longitude = $result->getLongitude();

        // Let's set some default settings.
        // More of them you can find in app\config\cconfig.yml.
        $map->setAutoZoom(false);
        $map->setCenter($latitude, $longitude, true);
        $map->setMapOption('zoom', 8);

        // $map->setStylesheetOptions(array(
        //     'width'  => '100%',
        //     'height' => '100%',
        // ));

        return $this->render('IPGEventsBundle:Gmap:index.html.twig', array('map' => $map));
    }
}
