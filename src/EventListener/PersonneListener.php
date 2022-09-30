<?php

namespace App\EventListener;

use App\Event\AddPersonneEvent;
use Psr\Log\LoggerInterface;

class PersonneListener
{
public function __construct(private LoggerInterface $logger)
{
    
}
    public function onPersonneAdd(AddPersonneEvent $event)
    {
        $this->logger->debug("Une personne vient d'être ajouté". $event->getPersonne()->getName());
    }
}
