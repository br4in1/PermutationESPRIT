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
    public function emploisAction(Request $request){
        $parameters = array(
            'isPdfOutsideWebroot' => false,
            'pdf' => '../../../assets/test.pdf',
            'deletePdfInTmpAfterRenderized' => false,
            'showToolBar' => true,
            'showLeftToolbarButton' => true,
            'showSearchInDocumentButton' => true,
            'showPreviousPageButton' => true,
            'showPreviousPageButton' => true,
            'showFindPageInputText' => true,
            'showNumberOfPagesLabel' => true,
            'showZoomInButton'=> false,
            'showZoomOutButton'=> false,
            'showScaleSelectComboBox'=> false,
            'showPresentationModeButton'=> true,
            'showOpenFileButton'=> true,
            'showPrintButton'=> true,
            'showDownloadButton'=> true,
            'showViewBookmarkButton'=> true,
            'showToolsButton'=> true,
        );
        return $this->get('jjalvarezl_pdfjs_viewer.viewer_controller')->renderCustomViewer($parameters);
    }

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
        $em = $this->getDoctrine()->getManager();
        $colocs = $em->getRepository("AppBundle:Colocation")->findBy(array("user" => $this->getUser()));
        $covs = $em->getRepository('AppBundle:Covoiturage')->findBy(array("user" => $this->getUser()));
        return $this->render('@App/default/profile.html.twig', [
            'user' => $em->getRepository("AppBundle:User")->findOneBy(array("id" => $this->getUser()->getId())),
            'colocs' => $colocs,
            'covs' => $covs
        ]);
    }

    public function UserProfileAction(Request $request,$id)
    {
        if($id === -1) return $this->render('@App/default/404.html.twig');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("AppBundle:User")->findOneBy(array("id" => $id));
        $colocs = $em->getRepository("AppBundle:Colocation")->findBy(array("user" => $user),array('added' => 'DESC'),10);
        $covs = $em->getRepository('AppBundle:Covoiturage')->findBy(array("user" => $user),array('date' => 'DESC'),10);
        if($user === null) return $this->render('@App/default/404.html.twig');
        return $this->render('@App/default/profile.html.twig', [
            'user' => $user,
            'colocs' => $colocs,
            'covs' => $covs
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

    public function changeUserPhotoAction(Request $request){
        if($request->isXmlHttpRequest()){
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $request->files->get('file-0');
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                '../assets/images/users_pics',
                $fileName
            );
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $user->setPicture($fileName);
            $em->persist($user);
            $em->flush();
            return new JsonResponse(array('success' => true,"url" => '/assets/images/users_pics/'.$fileName));
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
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
        $user->setPicture("_1.png");
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
