<?php

namespace Chill\ActivityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Chill\ActivityBundle\Entity\ActivityType;
use Chill\ActivityBundle\Form\ActivityTypeType;

/**
 * ActivityType controller.
 *
 */
class ActivityTypeController extends Controller
{

    /**
     * Lists all ActivityType entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ChillActivityBundle:ActivityType')->findAll();

        return $this->render('ChillActivityBundle:ActivityType:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ActivityType entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ActivityType();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chill_activity_activitytype_show', array('id' => $entity->getId())));
        }

        return $this->render('ChillActivityBundle:ActivityType:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ActivityType entity.
     *
     * @param ActivityType $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ActivityType $entity)
    {
        $form = $this->createForm(new ActivityTypeType(), $entity, array(
            'action' => $this->generateUrl('chill_activity_activitytype_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ActivityType entity.
     *
     */
    public function newAction()
    {
        $entity = new ActivityType();
        $form   = $this->createCreateForm($entity);

        return $this->render('ChillActivityBundle:ActivityType:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ActivityType entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChillActivityBundle:ActivityType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActivityType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ChillActivityBundle:ActivityType:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ActivityType entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChillActivityBundle:ActivityType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActivityType entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ChillActivityBundle:ActivityType:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ActivityType entity.
    *
    * @param ActivityType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ActivityType $entity)
    {
        $form = $this->createForm(new ActivityTypeType(), $entity, array(
            'action' => $this->generateUrl('chill_activity_activitytype_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ActivityType entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChillActivityBundle:ActivityType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActivityType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chill_activity_activitytype_edit', array('id' => $id)));
        }

        return $this->render('ChillActivityBundle:ActivityType:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ActivityType entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ChillActivityBundle:ActivityType')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ActivityType entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('chill_activity_activitytype'));
    }

    /**
     * Creates a form to delete a ActivityType entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('chill_activity_activitytype_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
