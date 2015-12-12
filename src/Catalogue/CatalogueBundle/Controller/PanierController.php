<?php

namespace Catalogue\CatalogueBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Panier Controller qui permette de gérer l'ensemble des action associer a un objet Panier.
 **/
class PanierController extends Controller
{
        //l'appel a cette fonction par ajax
        //permete d'ajouter un produit dans panier
    public function ajouterAction($id)
    {

        //récupéré de session courant
        $session = $this->getRequest()->getSession();

        //si session panier  n'existe pas dons la table session on crée un table nome panier
        if (!$session->has('panier')) $session->set('panier',array());
        $panier = $session->get('panier');
        //on effectué une test sur panier dons le cas où on'a pas le droit de commander même objet 2 fois
        //test l'existence de id (produit commander) dans table panier qui existe dans session
        //array_key_exists fonction qui test l'xistance de id dons les cle de table
        if (array_key_exists($id, $panier)) {
            //modifié Quantité avec succès
            if ($this->getRequest()->query->get('qte') != null) $panier[$id] = $this->getRequest()->query->get('qte');
        } else {
            if ($this->getRequest()->query->get('qte') != null)
                $panier[$id] = $this->getRequest()->query->get('qte');
            else
                // l'ajouté d'un Article avec succès
                $panier[$id] = 1;

        }
        //récupère nombre des produits dans panier pour calculer réduction.
        $nbrProduit=count($panier);
        $session->set('panier',$panier);

        $arrayForJson = array(
            'error' => false,
            //return vue de etats
            'vue' => $this->renderView('CatalogueBundle:Panier:etats.html.twig', array(
                    'nbproduit' => $nbrProduit,
                )),
            //return un id de produit commandé pour le cas d'un actualiser de la page le colleur des bouton reste rouge
            'cmd' => $this->renderView('CatalogueBundle:Produit:commande.html.twig', array(
                    'idProduit' => $id,
                )),
        );
        return new JsonResponse($arrayForJson);
        //le résultat de fonction toujours Json pour trait en ajax
    }

    //action qui affiche les produit commander par un client dans un table
    // retourne l'offre courant si existe pour but de calculer le réduction
    public function panierAction()
    {
        $session = $this->getRequest()->getSession();
        if (!$session->has('panier')) $session->set('panier', array());

        $em = $this->getDoctrine()->getManager();
        //appelé a la fonction findArray qui définie en repository pour récupéré tout les produit option qui existe dans panier
        $produit = $em->getRepository('CatalogueBundle:ProduitOption')->findArray(array_keys($session->get('panier')));
        $offrecourant = $em->getRepository('CatalogueBundle:Offre')->offrescourant();

        return $this->render('CatalogueBundle:Panier:panier.html.twig',
            array('produits' => $produit,'offres' => $offrecourant,
            'panier' => $session->get('panier')));
    }


    //fonction qui supprime un produit de panier l'appel a cette fonction par ajax
    public function supprimerAction($id)
    {
        $session = $this->getRequest()->getSession();
        $panier = $session->get('panier');
        //tester si le id produit dans panier on suprime
        if (array_key_exists($id, $panier))
        {
            unset($panier[$id]);
            $session->set('panier',$panier);
        }
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('CatalogueBundle:ProduitOption')->findArray(array_keys($session->get('panier')));
        $offrecourant = $em->getRepository('CatalogueBundle:Offre')->offrescourant();
        //cree et envoyer sous format d'un fichier JSON qui contient les produits aprer la supresion d'un produit de panier
        $arrayForJson = array(
            'error' => false,
            'vue' => $this->renderView('CatalogueBundle:Panier:table.html.twig', array(
                    'produits' =>$produit,'offres' => $offrecourant ,
                )),
        );
        return new JsonResponse($arrayForJson);
    }
}
