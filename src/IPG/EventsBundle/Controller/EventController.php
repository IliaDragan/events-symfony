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
        $form = $this->createForm(new EventType(), new Event());

        return array('form' => $form->createView());
    }
}
