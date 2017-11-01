<?php

namespace peter\components\serviceBundle;

// use peter\components\serviceBundle\Mailgun as Mailgun;

class ServiceFactory
{
    public function create($service) 
    {
        switch ($service) {
                case 'mailgun':
                    return new Mailgun();
                    break;
                case 'imgur':
                    return new Imgur();
                    break;
                default:
                    throw new \Exception('Service does not exist.');
        }
        // if(class_exists($service)) {
        //     return new $service();
        // } 
        // throw new \Exception('Service does not exist.');
    }
}
