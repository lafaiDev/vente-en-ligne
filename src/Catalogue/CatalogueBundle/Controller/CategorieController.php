<?php

namespace Catalogue\CatalogueBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Catalogue\CatalogueBundle\Entity\Categorie;
use Catalogue\CatalogueBundle\Form\CategorieType;

/**
 * Categorie controller Gerie les catégories.
 *
 */
class CategorieController extends Controller
{
    //return tous les catégorie pour menu de la page d'accueil
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('CatalogueBundle:Categorie')->findAll();
        return $this->render('CatalogueBundle:Categorie:menu.html.twig', array('categories' => $categories));
    }

    /**
     * Lister des Catégorie pour la page admin
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('CatalogueBundle:Categorie')->findAll();
        return $this->render('CatalogueBundle:Categorie:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     *  création d’entités catégorie et stocker dons la base de donnée.
     */
    public function createAction(Request $request)
    {
        $entity = new Categorie();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //a la fin de creation de categorie il faut mettre a jour le tableux des $categories automatiquement
            $categories = $em->getRepository('CatalogueBundle:Categorie')->findAll();
            //cree et envoyer sous format d'un fichier JSON qui contient les catégories aprer la ajoute d'un $categorie
            $arrayForJson = array(
                'error' => false,
                'vue' => $this->renderView('CatalogueBundle:Categorie:table.html.twig', array(
                        'entities' =>$categories,
                    )),
            );
            //le format de l'ensemble  des categories retourner et JSON grace a JsonResponse
            return new JsonResponse($arrayForJson);
        }

    }

    /**
     *
     * @param Categorie $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Categorie $entity)
    {
        $form = $this->createForm(new CategorieType(), $entity, array(
            'action' => $this->generateUrl('categorie_create'),
            'method' => 'POST',
        ));
        return $form;
    }

    /**
     *  crée un objet catégorie et form vide et envoyer pour initialiser li formulaire de création de catégorie
     */
    public function newAction()
    {
        $entity = new Categorie();
        $form   = $this->createCreateForm($entity);
        //cree un objet categorie pour objectif de cree un form vide afficher a l'utilisateur
        return $this->render('CatalogueBundle:Categorie:form.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }


    /**
     * modifier entities categorie.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CatalogueBundle:Categorie')->find($id);
        //teste l'existence de catégorie
        if (!$entity) {
            throw $this->createNotFoundException('pas de categorie.');
        }
        //creation une form d'edite apartire de CategorieType
        $editForm = $this->createEditForm($entity);

        //cree un objet produit pour objectif de cree un form qui afficher a l'utilisateur le produit selectioner
        return $this->render('CatalogueBundle:Categorie:form.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Categorie entity.
    *
    * @param Categorie $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Categorie $entity)
    {
        $form = $this->createForm(new CategorieType(), $entity, array(
            'action' => $this->generateUrl('categorie_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        return $form;
    }
    /**
     * modifie est sauvegarder l'entities catégorie.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CatalogueBundle:Categorie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('pas de categorie.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        //vérification la validation de formulaire (les champ obligatoire)
        if ($editForm->isValid()) {
            $em->flush();

            //return $this->redirect($this->generateUrl('produit_edit', array('id' => $id)));
            //a la fin de creation de produit il faut mettre a jour le tableux des produits automatiquement
            $categories = $em->getRepository('CatalogueBundle:Categorie')->findAll();
            //creation d'n table pour les donnee a passer par JSON
            $arrayForJson = array(
                'error' => false,
                'vue' => $this->renderView('CatalogueBundle:Categorie:table.html.twig', array(
                        'entities' =>$categories,
                    )),
            );
            //la format de date'l'ensemble  des produit) retourner et JSON grace a JsonResponse
            return new JsonResponse($arrayForJson);
        }
    }
    /**
     * supprimer l’entities catégorie.
     */
    public function deleteAction(Request $request, $id)
    {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CatalogueBundle:Categorie')->find($id);
            $em->remove($entity);
            $em->flush();

            $categories = $em->getRepository('CatalogueBundle:Categorie')->findAll();

            //crée est envoyer sous format d'un fichier JSON qui contient les catégories après la suppression d'un catégorie
            $arrayForJson = array(
                'error' => false,
                'vue' => $this->renderView('CatalogueBundle:Categorie:table.html.twig', array(
                        'entities' =>$categories,
                    )),
            );
            return new JsonResponse($arrayForJson);

    }

    /**
     *  form pour supprission d'un Categorie entities par id.
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categorie_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
