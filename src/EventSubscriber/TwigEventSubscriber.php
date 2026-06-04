<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;
use App\Repository\ConferenceRepository;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private Environment $twig;
    private ConferenceRepository $conferenceRepository;

    public function __construct(Environment $twig, ConferenceRepository $conferenceRepository)
    {
        $this->twig = $twig;
        $this->conferenceRepository = $conferenceRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onControllerEvent',
        ];
    }

     public function onControllerEvent(ControllerEvent $event): void
     {
        // ...
        $this->twig->addGlobal('conferences', $this->conferenceRepository->findAll());
     }

}
