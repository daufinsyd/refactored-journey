<?php

namespace RASP\RaspBundle\Controller;

use donatj\SimpleCalendar;
use donatj\SimpleCalendarPlus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CalendarController extends Controller
{
    public function showCalendarAction($calendar_id){
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();

        $calendar = new SimpleCalendarPlus();
        $calendar->setDate("June 5 2010");
        $calendar->addDailyHtml("Coucou :)", "June 6 2010 10:7:02");
        $calendar->addDailyHtml("Coucou3 :)", "June 6 2010 11:8:03");
        $calendar->addDailyHtml("Coucou2 :)", "June 7 2010 12:9:04");

        return $this->render('@RASPRasp/Calendar/calendar.html.twig', array('loggedInUser' => $loggedInUser, "calendar" => $calendar));
    }


}