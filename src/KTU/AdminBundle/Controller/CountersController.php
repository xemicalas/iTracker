<?php

namespace KTU\AdminBundle\Controller;

use KTU\CountersBundle\Model\CounterStatisticsModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use KTU\AdminBundle\Entity\Counters;
use KTU\AdminBundle\Form\CountersType;

/**
 * Counters controller.
 *
 */
class CountersController extends Controller
{

    /**
     * Lists all Counters entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('KTUAdminBundle:Counters')->findAll();

        return $this->render('KTUAdminBundle:Counters:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Counters entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Counters();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('counters_show', array('id' => $entity->getId())));
        }

        return $this->render('KTUAdminBundle:Counters:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Counters entity.
    *
    * @param Counters $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Counters $entity)
    {
        $form = $this->createForm(new CountersType(), $entity, array(
            'action' => $this->generateUrl('counters_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Counters entity.
     *
     */
    public function newAction()
    {
        $entity = new Counters();
        $form   = $this->createCreateForm($entity);

        return $this->render('KTUAdminBundle:Counters:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Counters entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('KTUAdminBundle:Counters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Counters entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('KTUAdminBundle:Counters:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Counters entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('KTUAdminBundle:Counters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Counters entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('KTUAdminBundle:Counters:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Counters entity.
    *
    * @param Counters $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Counters $entity)
    {
        $form = $this->createForm(new CountersType(), $entity, array(
            'action' => $this->generateUrl('counters_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Counters entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('KTUAdminBundle:Counters')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Counters entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('counters_edit', array('id' => $id)));
        }

        return $this->render('KTUAdminBundle:Counters:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Counters entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('KTUAdminBundle:Counters')->find($id);
            $statistics = CounterStatisticsModel::getStatsByCounterId($em, $entity->getId());

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Counters entity.');
            }

            foreach ($statistics as $stat) {
                $em->remove($stat);
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('counters'));
    }

    /**
     * Creates a form to delete a Counters entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('counters_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
