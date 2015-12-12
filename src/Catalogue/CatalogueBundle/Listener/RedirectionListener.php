<?php
namespace Catalogue\CatalogueBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RedirectionListener
{
    public function __construct(ContainerInterface $container, Session $session)
    {
        $this->session = $session;
        $this->router = $container->get('router');
        $this->securityContext = $container->get('security.context');
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        //recuperation de route corent
        $route = $event->getRequest()->attributes->get('_route');

        if ( $route =='panier') {
            //pour aouter un artect il demande d'authentification
            //on vas verivier que l'utilisteur et bien conecter
            //si non en derige vers la page login
            if (!is_object($this->securityContext->getToken()->getUser())) {
                //$this->session->getFlashBag()->add('notification','Vous devez vous identifier !');
                $event->setResponse(new RedirectResponse($this->router->generate('fos_user_security_login')));
            }
            else{
                $event->setResponse(new RedirectResponse($this->router->generate('panier')));
            }


        }

    }
}