<?php

namespace RASP\RaspBundle\Controller;

use donatj\SimpleCalendar;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CalendarController extends Controller
{
    public function showCalendarAction($calendar_id){
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();

        $calendar = new SimpleCalendar();
        $calendar->setDate("June 5 2010");

        return $this->render('@RASPRasp/Calendar/calendar.html.twig', array('loggedInUser' => $loggedInUser, "calendar" => $calendar));
    }


}