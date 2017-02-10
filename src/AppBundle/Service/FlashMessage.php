<?php
/**
 * Created by PhpStorm.
 * User: glympero
 * Date: 09/02/2017
 * Time: 21:25
 */

namespace AppBundle\Service;
use Symfony\Component\HttpFoundation\Session\Session;

class FlashMessage
{
    private $session;
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getDangerMsg()
    {
        return $this->session->getFlashBag()->add('danger', 'Your initiative has been saved! - budget exceeded!');
    }

    public function getInfoMsg()
    {
        return $this->session->getFlashBag()->add('info', 'Your initiative has been saved! - budget not exceeded');
    }

    public function getSuccessMsg()
    {
        return $this->session->getFlashBag()->add('success', 'Your budget has been saved!');
    }
}