<?php

/*
 * Chill is a software for social workers
 *
 * Copyright (C) 2014-2015, Champs Libres Cooperative SCRLFS, 
 * <http://www.champs-libres.coop>, <info@champs-libres.coop>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Chill\ActivityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Chill\ActivityBundle\Entity\Activity;
use Chill\ActivityBundle\Form\ActivityType;
use Symfony\Component\Security\Core\Role\Role;
use Chill\PersonBundle\Entity\Person;

/**
 * Activity controller.
 *
 */
class ActivityController extends Controller
{

    /**
     * Lists all Activity entities.
     *
     */
    public function listAction($person_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('ChillPersonBundle:Person')->find($person_id);
        $activities = $em->getRepository('ChillActivityBundle:Activity')->findAll();

        return $this->render('ChillActivityBundle:Activity:list.html.twig', array(
            'activities' => $activities,
            'person'   => $person
        ));
    }
    /**
     * Creates a new Activity entity.
     *
     */
    public function createAction($person_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('ChillPersonBundle:Person')->find($person_id);
        
        /**if ($person === NULL) {
            throw $this->createNotFoundException('person not found');
        }*/
        
        $this->denyAccessUnlessGranted('CHILL_PERSON_SEE', $person);
        
        $entity = new Activity();
        $form = $this->createCreateForm($entity, $person);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chill_activity_activity_show', array('id' => $entity->getId())));
        }

        return $this->render('ChillActivityBundle:Activity:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'person' => $person
        ));
    }

    /**
     * Creates a form to create a Activity entity.
     *
     * @param Activity $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Activity $entity, Person $person)
    {
        $form = $this->createForm('chill_activitybundle_activity', $entity, 
              array(
                'action' => $this->generateUrl('chill_activity_activity_create', ['person_id' => $person->getId()]),
                'method' => 'POST',
                'center' => $person->getCenter(),
                'role'   => new Role('CHILL_ACTIVITY_CREATE')
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Activity entity.
     *
     */
    public function newAction($person_id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('ChillPersonBundle:Person')->find($person_id);
        
        $entity = new Activity();
        $form   = $this->createCreateForm($entity, $person);

        return $this->render('ChillActivityBundle:Activity:new.html.twig', array(
            'person'   => $person,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Activity entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChillActivityBundle:Activity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activity entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ChillActivityBundle:Activity:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Activity entity.
     *
     */
    public function editAction($person_id, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('ChillPersonBundle:Person')->find($person_id);

        $entity = $em->getRepository('ChillActivityBundle:Activity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activity entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ChillActivityBundle:Activity:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'person' => $$person
        ));
    }

    /**
    * Creates a form to edit a Activity entity.
    *
    * @param Activity $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Activity $entity)
    {
        $form = $this->createForm(new ActivityType(), $entity, array(
            'action' => $this->generateUrl('chill_activity_activity_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Activity entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChillActivityBundle:Activity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activity entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chill_activity_activity_edit', array('id' => $id)));
        }

        return $this->render('ChillActivityBundle:Activity:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Activity entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ChillActivityBundle:Activity')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Activity entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('activity'));
    }

    /**
     * Creates a form to delete a Activity entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('chill_activity_activity_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
