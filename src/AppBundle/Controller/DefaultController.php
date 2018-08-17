<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProfilePrefs;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    public function CurrentUserProfileAction(Request $request)
    {
        return $this->render('@App/default/profile.html.twig', [
            'user' => $this->getDoctrine()->getManager()->getRepository("AppBundle:User")->findOneBy(array("id" => $this->getUser()->getId()))
        ]);
    }

    public function UserProfileAction(Request $request,$id)
    {
        if($id === -1) return $this->render('@App/default/404.html.twig');
        $user = $this->getDoctrine()->getManager()->getRepository("AppBundle:User")->findOneBy(array("id" => $id));
        if($user === null) return $this->render('@App/default/404.html.twig');
        return $this->render('@App/default/profile.html.twig', [
            'user' => $user
        ]);
    }

    public function PhoneNumberAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $prefs = $em->getRepository('AppBundle:ProfilePrefs')->findOneBy(array('user' => $this->getUser()));
        $prefs->setPhonenumbervisible(!$prefs->isPhonenumbervisible());
        $em->persist($prefs);
        $em->flush();
        return new JsonResponse(array('success' => true));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request)
    {
        if($this->isGranted("ROLE_USER")) return $this->redirectToRoute("index");
        $user = $this->get('fos_user.user_manager')->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $this->get('event_dispatcher')->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->get('fos_user.registration.form.factory')->createForm();
        $form->setData($user);
        $firstname = ucfirst(substr($request->get('fos_user_registration_form')["email"],0,strpos($request->get('fos_user_registration_form')["email"],'.')));
        $lastname = ucfirst(substr($request->get('fos_user_registration_form')["email"],strpos($request->get('fos_user_registration_form')["email"],'.')+1,strpos($request->get('fos_user_registration_form')["email"],'@')-strpos($request->get('fos_user_registration_form')["email"],'.')-1));
        $user->setFirstname(preg_replace('/[0-9]+/', '',$firstname));
        $user->setLastname(preg_replace('/[0-9]+/', '',$lastname));
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $this->get('event_dispatcher')->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $this->get('fos_user.user_manager')->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $this->get('event_dispatcher')->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $this->get('event_dispatcher')->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        //return $this->render('@FOSUser/Registration/register.html.twig', array('form' => $form->createView(),));
        return $this->render('@FOSUser/Registration/register_content.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
