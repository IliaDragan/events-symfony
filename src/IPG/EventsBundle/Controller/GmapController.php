<?php

namespace IPG\EventsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

// FB Debug
require_once('/usr/share/php/FirePHPCore/fb.php');

class GmapController extends Controller
{
    /**
     * @Route("/map")
     *
     * @var Ivory\GoogleMapBundle\Model\Map
     */
    public function indexAction()
    {
        $map = $this->get('ivory_google_map.map');
        // $view['ivory_google_map']->renderHtmlContainer($map);

        return $this->render('IPGEventsBundle:Gmap:index.html.twig', array('map' => $map));
    }
}
