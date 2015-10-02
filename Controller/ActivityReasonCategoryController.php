<?php

namespace Chill\ActivityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Chill\ActivityBundle\Entity\ActivityReasonCategory;
use Chill\ActivityBundle\Form\ActivityReasonCategoryType;

/**
 * ActivityReasonCategory controller.
 *
 */
class ActivityReasonCategoryController extends Controller
{

    /**
     * Lists all ActivityReasonCategory entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ChillActivityBundle:ActivityReasonCategory')->findAll();

        return $this->render('ChillActivityBundle:ActivityReasonCategory:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ActivityReasonCategory entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ActivityReasonCategory();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chill_activity_activityreasoncategory_show', array('id' => $entity->getId())));
        }

        return $this->render('ChillActivityBundle:ActivityReasonCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ActivityReasonCategory entity.
     *
     * @param ActivityReasonCategory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ActivityReasonCategory $entity)
    {
        $form = $this->createForm(new ActivityReasonCategoryType(), $entity, array(
            'action' => $this->generateUrl('chill_activity_activityreasoncategory_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ActivityReasonCategory entity.
     *
     */
    public function newAction()
    {
        $entity = new ActivityReasonCategory();
        $form   = $this->createCreateForm($entity);

        return $this->render('ChillActivityBundle:ActivityReasonCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ActivityReasonCategory entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChillActivityBundle:ActivityReasonCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActivityReasonCategory entity.');
        }

        return $this->render('ChillActivityBundle:ActivityReasonCategory:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing ActivityReasonCategory entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChillActivityBundle:ActivityReasonCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActivityReasonCategory entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('ChillActivityBundle:ActivityReasonCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ActivityReasonCategory entity.
    *
    * @param ActivityReasonCategory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ActivityReasonCategory $entity)
    {
        $form = $this->createForm(new ActivityReasonCategoryType(), $entity, array(
            'action' => $this->generateUrl('chill_activity_activityreasoncategory_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ActivityReasonCategory entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChillActivityBundle:ActivityReasonCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActivityReasonCategory entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chill_activity_activityreasoncategory_edit', array('id' => $id)));
        }

        return $this->render('ChillActivityBundle:ActivityReasonCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
}
