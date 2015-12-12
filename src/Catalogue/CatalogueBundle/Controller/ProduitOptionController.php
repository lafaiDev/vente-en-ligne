<?php

namespace Catalogue\CatalogueBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Catalogue\CatalogueBundle\Entity\ProduitOption;
use Catalogue\CatalogueBundle\Form\ProduitOptionType;

/**
 * ProduitOption controller.
 *
 */
class ProduitOptionController extends Controller
{

    /**
     * Lists all ProduitOption entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CatalogueBundle:ProduitOption')->findAll();

        return $this->render('CatalogueBundle:ProduitOption:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ProduitOption entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ProduitOption();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('produitOption_show', array('id' => $entity->getId())));
        }

        return $this->render('CatalogueBundle:ProduitOption:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ProduitOption entity.
     *
     * @param ProduitOption $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ProduitOption $entity)
    {
        $form = $this->createForm(new ProduitOptionType(), $entity, array(
            'action' => $this->generateUrl('produitOption_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ProduitOption entity.
     *
     */
    public function newAction()
    {
        $entity = new ProduitOption();
        $form   = $this->createCreateForm($entity);

        return $this->render('CatalogueBundle:ProduitOption:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ProduitOption entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatalogueBundle:ProduitOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProduitOption entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CatalogueBundle:ProduitOption:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ProduitOption entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatalogueBundle:ProduitOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProduitOption entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CatalogueBundle:ProduitOption:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ProduitOption entity.
    *
    * @param ProduitOption $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ProduitOption $entity)
    {
        $form = $this->createForm(new ProduitOptionType(), $entity, array(
            'action' => $this->generateUrl('produitOption_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ProduitOption entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CatalogueBundle:ProduitOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProduitOption entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('produitOption_edit', array('id' => $id)));
        }

        return $this->render('CatalogueBundle:ProduitOption:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ProduitOption entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CatalogueBundle:ProduitOption')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ProduitOption entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('produitOption'));
    }

    /**
     * Creates a form to delete a ProduitOption entity by id.
     *
     * @param mixed $id The entity d
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('produitOption_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
