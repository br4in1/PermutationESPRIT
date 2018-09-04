<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Permutation_Classes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Permutation_class controller.
 *
 */
class Permutation_ClassesController extends Controller
{
    /**
     * Lists all permutation_Class entities.
     *
     */
    public function indexAction(Request $request)
    {
        if($this->getUser()->getClasse() === "" || $this->getUser()->getClasse() === null){
            $request->getSession()->set('classError',1);
            return $this->redirectToRoute('fos_user_profile_edit');
        }
        $em = $this->getDoctrine()->getManager();

        $permutation_Classes = $em->getRepository('AppBundle:Permutation_Classes')->findAll();

        return $this->render('@App/permutation_classes/index.html.twig', array(
            'permutation_Classes' => $permutation_Classes,
        ));
    }

    /**
     * Creates a new permutation_Class entity.
     *
     */
    public function newAction(Request $request)
    {
        $spec = strtoupper(preg_replace('/[0-9]/','',$this->getUser()->getClasse()));
        $session= new Session();
        $flashbag = $session->getFlashBag();
        $em = $this->getDoctrine()->getManager();
        $permutations = $em->getRepository('AppBundle:Permutation_Classes')->findAll();
        $em = $this->getDoctrine()->getManager();
        $test = $em->getRepository('AppBundle:Permutation_Classes')->findBy(
            ['user' => $this->getUser(), 'classe' => $request->get('destination')]);

        if($request->get('destination') == $this->getUser()->getClasse()){
            $flashbag->add('error' , 'La classe de départ et de destination doivent être différentes');
        }
        else if($test != NULL){
            $flashbag->add('error' , 'Vous avez déja saisi une permutation vers cette classe.');
        }
        else if(!preg_match("/".$this->getUser()->getClasse()[0].$spec."[0-9]+$/",$request->get('destination'))){
            $flashbag->add('error' , "Soit la classe est invalide soit vous êtes en train d'effectuer une permutation vers une autre specialité ou niveau.");
        }
        else{
            $permutation = new Permutation_Classes();
            $permutation->setClasse($request->get('destination'));
            $permutation->setDate(new \DateTime("now"));
            $permutation->setState(1);
            $permutation->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($permutation);
            $em->flush();
            return $this->redirectToRoute('permutation_classes_index');
        }
        return $this->redirectToRoute('permutation_classes_index');
    }

    /**
     * Finds and displays a permutation_Class entity.
     *
     */
    public function showAction(Permutation_Classes $permutation_Classes)
    {
        $deleteForm = $this->createDeleteForm($permutation_Classes);

        return $this->render('@App/permutation_classes/show.html.twig', array(
            'permutation_Classes' => $permutation_Classes,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing permutation_Class entity.
     *
     */
    public function editAction(Request $request, Permutation_Classes $permutation_Classes)
    {
        $deleteForm = $this->createDeleteForm($permutation_Classes);
        $editForm = $this->createForm('AppBundle\Form\Permutation_ClassesType', $permutation_Classes);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('permutation_classes_edit', array('id' => $permutation_Classes->getId()));
        }

        return $this->render('@App/permutation_classes/edit.html.twig', array(
            'permutation_Classes' => $permutation_Classes,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a permutation_Class entity.
     *
     */
    public function deleteAction(Request $request, Permutation_Classes $permutation_Classes)
    {
        $form = $this->createDeleteForm($permutation_Classes);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($permutation_Classes);
            $em->flush();
        }

        return $this->redirectToRoute('permutation_classes_index');
    }

    /**
     * Creates a form to delete a permutation_Class entity.
     *
     * @param Permutation_Classes $permutation_Class The permutation_Class entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Permutation_Classes $permutation_Classes)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('permutation_classes_delete', array('id' => $permutation_Classes ->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
