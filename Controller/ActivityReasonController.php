<?php

namespace Chill\ActivityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Chill\ActivityBundle\Entity\ActivityReason;
use Chill\ActivityBundle\Form\ActivityReasonType;

/**
 * ActivityReason controller.
 *
 */
class ActivityReasonController extends Controller
{

    /**
     * Lists all ActivityReason entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ChillActivityBundle:ActivityReason')->findAll();

        return $this->render('ChillActivityBundle:ActivityReason:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ActivityReason entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ActivityReason();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chill_activity_activityreason_show', array('id' => $entity->getId())));
        }

        return $this->render('ChillActivityBundle:ActivityReason:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ActivityReason entity.
     *
     * @param ActivityReason $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ActivityReason $entity)
    {
        $form = $this->createForm(new ActivityReasonType(), $entity, array(
            'action' => $this->generateUrl('chill_activity_activityreason_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ActivityReason entity.
     *
     */
    public function newAction()
    {
        $entity = new ActivityReason();
        $form   = $this->createCreateForm($entity);

        return $this->render('ChillActivityBundle:ActivityReason:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ActivityReason entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChillActivityBundle:ActivityReason')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActivityReason entity.');
        }

        return $this->render('ChillActivityBundle:ActivityReason:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing ActivityReason entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChillActivityBundle:ActivityReason')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActivityReason entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('ChillActivityBundle:ActivityReason:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
    * Creates a form to edit a ActivityReason entity.
    *
    * @param ActivityReason $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ActivityReason $entity)
    {
        $form = $this->createForm(new ActivityReasonType(), $entity, array(
            'action' => $this->generateUrl('chill_activity_activityreason_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ActivityReason entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChillActivityBundle:ActivityReason')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActivityReason entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chill_activity_activityreason_edit', array('id' => $id)));
        }

        return $this->render('ChillActivityBundle:ActivityReason:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }
}
