<?php

namespace Catalogue\CatalogueBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Catalogue\CatalogueBundle\Entity\Offre;
use Catalogue\CatalogueBundle\Form\OffreType;

/**
 * Offre controller permette de gère les offres.
 *
 */
class OffreController extends Controller
{
    //fonction qui permette de retourné les offre courent
    public function offrecorentAction()
    {
        $em = $this->getDoctrine()->getManager();
        //appelé a la fonction offrescourant() dans repository qui permette
        //de crée une date courant et récupéré l'ensemble des offre courant est active
        $entities = $em->getRepository('CatalogueBundle:Offre')->offrescourant();
        return $this->render('CatalogueBundle:Offre:offrescourant.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Lists tous les Offres entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CatalogueBundle:Offre')->findAll();

        return $this->render('CatalogueBundle:Offre:index.html.twig', array(
            'entities' => $entities,

        ));
    }
    //les offre active
    public function offreactiveAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CatalogueBundle:Offre')->findactiveoffre();

        return $this->render('CatalogueBundle:Offre:offresactive.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Cree un Offre
     */
    public function createAction(Request $request)
    {
        $entity = new Offre();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        //verifier si le form et valide
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('offre', array('id' => $entity->getId())));
        }

        return $this->render('CatalogueBundle:Offre:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creation d'une form pour cree les Offres entities.
     *
     * @param Offre $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Offre $entity)
    {
        $form = $this->createForm(new OffreType(), $entity, array(
            'action' => $this->generateUrl('offre_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * une form pour creation un Offre entities.
     *
     */
    public function newAction()
    {
        $entity = new Offre();
        $form   = $this->createCreateForm($entity);

        return $this->render('CatalogueBundle:Offre:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * afficher un Offre entity paar sont id
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatalogueBundle:Offre')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('pas d offre demander !!!.');
        }
        //form pour supprision
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CatalogueBundle:Offre:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * une form to edit un Offre entities exiiste.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CatalogueBundle:Offre')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('pas Offre.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('CatalogueBundle:Offre:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Offre entity.
    *
    * @param Offre $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Offre $entity)
    {
        $form = $this->createForm(new OffreType(), $entity, array(
            'action' => $this->generateUrl('offre_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }
    /**
     * modification d'un Offre entitie existe.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CatalogueBundle:Offre')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('pas offre!!');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('offre'));
        }

    }
    /**
     * fonction pour but supprission des offre .
     */
    public function deleteAction(Request $request, $id)
    {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CatalogueBundle:Offre')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Offre entity.');
            }

            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('offre'));
    }

    /**
     * Creates a form to delete a Offre entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('offre_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
        ;
    }
}
