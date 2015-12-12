<?php

namespace Catalogue\CatalogueBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Catalogue\CatalogueBundle\Entity\Produit;
use Catalogue\CatalogueBundle\Entity\Categorie;
use Catalogue\CatalogueBundle\Form\ProduitType;

/**
 * Produit Controller qui permette de gérer l'ensemble des actions associées à un objet produit.
 */
class ProduitController extends Controller
{

    // afficher les produit par catégories pour les clients
    public function produitsAction(Categorie $categorie = null)
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();

        //test si la table des categoeries != null
        if ($categorie != null){
            $findProduits = $em->getRepository('CatalogueBundle:Produit')->byCategorie($categorie);

        }else{
            $findProduits=null;
        }

        //les offre active pour la page d'acceuil
        $offres = $em->getRepository('CatalogueBundle:Offre')->findactiveoffre();
        //les offre active courant
        $offrecourant = $em->getRepository('CatalogueBundle:Offre')->offrescourant();
        //gerer la sesion panier
        if ($session->has('panier'))
            $panier = $session->get('panier');
        else
            $panier = false;


        //on limiter le nombre des produits afficher par page en 3 produits
        $produits = $this->get('knp_paginator')->paginate($findProduits,$this->get('request')->query->get('page', 1),3);

        //le resulta et affiche a page produits.html.twig
        return $this->render('CatalogueBundle:Produit:produits.html.twig',
            array('produits' => $produits,'offres' => $offres,'offrescourant' => $offrecourant));
    }

    //afficher tous les produits pour lister

    public function allProduitsAction()
    {

        $em = $this->getDoctrine()->getManager();
        //récupération de tous les produits
        $findProduits = $em->getRepository('CatalogueBundle:Produit')->findBy(array(), array('id' => 'desc'));

        //les offre active pour la page d'acceuil
        $offres = $em->getRepository('CatalogueBundle:Offre')->findactiveoffre();

        //les offre active courant
        $offrecourant = $em->getRepository('CatalogueBundle:Offre')->offrescourant();

        //on limiter le nombre des produits afficher par page en 3 produits par categorie
        $produits = $this->get('knp_paginator')->paginate($findProduits,$this->get('request')->query->get('page', 1),3);

        return $this->render('CatalogueBundle:Produit:produits.html.twig',
            array('produits' => $produits,'offres' => $offres,'offrescourant' => $offrecourant));
    }
    /**
     * retourn les Produits entities pour un administarteur.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CatalogueBundle:Produit')->findBy(array(), array('id' => 'desc'));

        return $this->render('CatalogueBundle:Produit:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates Produit entities.
     *l'appel a cette fonction par ajax
     */
    public function createAction(Request $request)
    {
        //cretion d'un objet produit
        $entity = new Produit();
        //cretion d'un form
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        //verification si le form et valide
        if ($form->isValid()) {
            //on récupère l'option de produit pour ajouter à la table option l'id produit avec nouvel option
            $options = $entity->getOptionsProduit();
            foreach($options as $option){
                $option->setProduit($entity);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //à la fin de création de produit il faut mettre à jour le tableaux des produits automatiquement
            $Produits = $em->getRepository('CatalogueBundle:Produit')->findAll();
            //creation d'un table pour les donnee a passer par JSON
            $arrayForJson = array(
                'error' => false,
                'vue' => $this->renderView('CatalogueBundle:Produit:table.html.twig', array(
                        'entities' =>$Produits,
                    )),
            );
            //la format de date'l'ensemble  des produit) retourner et JSON grace a JsonResponse
            return new JsonResponse($arrayForJson);
        }
    }

    /**
     * Creates un form pour cree un Produit entitie
     *
     * @param Produit $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Produit $entity)
    {
        $form = $this->createForm(new ProduitType(), $entity, array(
            'action' => $this->generateUrl('produit_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * cree un form Produit
     *l'appel a cette fonction par ajax
     */
    public function newAction()
    {
        $entity = new Produit();
        $form   = $this->createCreateForm($entity);

        //cree un objet produit pour objectif de cree un form vide afficher a l'utilisateur
        return $this->render('CatalogueBundle:Produit:form.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }



    /**
     * un form pour edit un Produit existe
     *l'appel a cette fonction par ajax
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        //récupère le produit
        $entity = $em->getRepository('CatalogueBundle:Produit')->find($id);

        //Exception pour le cas pas de produit (traite le cas de demande éditer passer par URL avec un produit n’existe pas )
        if (!$entity) {
            throw $this->createNotFoundException('pas de produits!!');
        }

        $editForm = $this->createEditForm($entity);

        //crée un objet produit pour objectif de crée un form qui afficher a l'utilisateur le produit sélectionner
        return $this->render('CatalogueBundle:Produit:form.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creation d'un form pour editer un Produit entities.
    *
    * @param Produit $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Produit $entity)
    {
        $form = $this->createForm(new ProduitType(), $entity, array(
            'action' => $this->generateUrl('produit_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        return $form;
    }
    /**
     * modifier un Produit existe.
     *l'appel a cette fonction par ajax
     */
    public function updateAction(Request $request, $id)
    {
        //creation d'un entitie manager
        $em = $this->getDoctrine()->getManager();
        //récupéré un produit par son id
        $entity = $em->getRepository('CatalogueBundle:Produit')->find($id);
        //Exception pour le cas pas de produit (traite le cas de demande éditer passer par URL avec un produit n’existe pas )
        if (!$entity) {
            throw $this->createNotFoundException('Impossible de trouver Produit entités.');
        }
        //creation de 2 variable Delete ;create
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        //vérification la validation de formulaire (les champ obligatoire)
        if ($editForm->isValid()) {
            $em->flush();

            //return $this->redirect($this->generateUrl('produit_edit', array('id' => $id)));
            //a la fin de creation de produit il faut mettre a jour le tableux des produits automatiquement
            $Produits = $em->getRepository('CatalogueBundle:Produit')->findBy(array(), array('id' => 'desc'));
            //creation d'n table pour les donnee a passer par JSON
            $arrayForJson = array(
                'error' => false,
                'vue' => $this->renderView('CatalogueBundle:Produit:table.html.twig', array(
                        'entities' =>$Produits,
                    )),
            );
            //le format de donnee ( l'ensemble  des produit) retourner et JSON grace a JsonResponse
            return new JsonResponse($arrayForJson);
        }

    }
    /**
     * suppresion d'un Produit
     *
     */
    public function deleteAction(Request $request, $id)
    {

        //supprimer le produit de id et il faut mettre a jour le tableux des produits
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CatalogueBundle:Produit')->find($id);
        $em->remove($entity);
        $em->flush();

        $produits = $em->getRepository('CatalogueBundle:Produit')->findBy(array(), array('id' => 'desc'));
        //cree et envoyer sous format d'un fichier JSON qui contient les produits aprer la supresion d'un produit
        $arrayForJson = array(
            'error' => false,
            'vue' => $this->renderView('CatalogueBundle:Produit:table.html.twig', array(
                    'entities' =>$produits,
                )),
        );
        return new JsonResponse($arrayForJson);

    }

    /**
     *Creation de  form pour supprimer un Produit entities par id.
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('produit_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
