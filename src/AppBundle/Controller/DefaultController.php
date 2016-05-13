<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Formation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
      $kernel = new \AppKernel('prod', true);

      $kernel->boot();

      /** @var $router \Symfony\Component\Routing\Router */
      $router = $kernel->getContainer()->get('router');

      $router->setOption('debug', true);
      /** @var $collection \Symfony\Component\Routing\RouteCollection */
      $collection = $router->getRouteCollection();
      $allRoutes = $collection->all();
        return $this->render('home.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
        ->add('email', EmailType::class)
        ->add('username', TextType::class)
        ->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password')))
        ->add('firstname', TextType::class, array('label' => 'Firstname'))
        ->add('lastname', TextType::class, array('label' => 'Lastename'))
        ->add('birthdate', BirthdayType::class, array('label' => 'Birth date'))
        ->add('sex', TextType::class, array('label' => "Sex ('M' or 'F')"))
        ->add('entry', DateType::class, array('label' => 'Entry date'))
        ->add('contract', TextType::class, array('label' => "Contract type ('CDD' or 'CDI')"))
        ->add('duration', IntegerType::class, array('label' => "Contract duration (month)"))
        ->add('salary', IntegerType::class, array('label' => "Salary"))
        ->add('superior', IntegerType::class, array('label' => "Superior (id)"))
        ->add('save', SubmitType::class, array('label' => 'Create user'))
        ->getForm();

        // tester si le formulaire est déjà rempli
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('created');
            //return $this->redirectToRoute('created', array('url' => $url));
        }

        // afficher le formulaire s'il n'est pas déjà rempli
        return $this->render('register.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        return $this->render('test/bootstrap-dashboard.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/theme", name="theme")
     */
    public function themeAction(Request $request)
    {
        return $this->render('test/bootstrap-theme.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/directory",name="directory")
     */
    public function annuaireAction(Request $request)
    {
        return $this->render('directory.html.twig');
    }

    /**
     * @Route("/userlist",name="userlist")
     */

    public function GetUsersAction(Request $req){
        if($req->isXmlHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT u
                FROM AppBundle:User u
                WHERE u.username LIKE :r OR u.email LIKE :r OR u.firstname LIKE :r OR u.lastname LIKE :r
                ORDER BY u.username ASC'
            )->setParameter('r', "%".$req->get('recherche')."%");

            return new JsonResponse(array('data' => json_encode($query->getResult())));
        }
        return new Response("Erreur : Ce n'est pas une requête Ajax",400);
    }

    /**
     * @Route("/newformation", name="newformation")
     */
    public function newformationAction(Request $request)
    {
        $formation = new Formation();

        $form = $this->createFormBuilder($formation)
        ->add('name', TextType::class, array('label' => 'Name of the formation'))
        ->add('contents', TextareaType::class, array('label' => 'Contents', 'attr' => array('rows' => '11')))
        ->add('date', DateType::class, array('label' => 'Date'))
        ->add('cost', IntegerType::class, array('label' => 'Cost'))
        ->add('duration', IntegerType::class, array('label' => 'Duration in days'))
        ->add('prerequisites', TextareaType::class, array('label' => 'Prerequisites', 'attr' => array('rows' => '5')))
        ->add('submit', SubmitType::class, array('label' => 'Create formation'))
        ->getForm();

        // tester si le formulaire est déjà rempli
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute('formations');
            //return $this->redirectToRoute('created', array('url' => $url));
        }

        // afficher le formulaire s'il n'est pas déjà rempli
        return $this->render('formations/newformation.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/formations", name="formations")
     */
    public function formationsAction(Request $request){
        $formations = $this->getDoctrine()->getRepository("AppBundle:Formation")->findAll();
        return $this->render("formations/formations.html.twig", ['formations' => $formations]);
    }


    /**
     * @Route("/formation/{id}",name="formation")
     */
    public function formationAction($id, Request $request){
        $formation = $this->getDoctrine()->getRepository('AppBundle:Formation')->findOneById($id);

        if(!$formation){
            throw $this->createNotFoundException("No formation with this id : ".$id);
        }

        return $this->render("formations/formation.html.twig",['formation' => $formation]);
    }

    /**
     * @Route("/formationapplicants",name="formationapplicants")
     */

    public function GetApplicantsAction(Request $req){
        if($req->isXmlHttpRequest()) {

            $formation = $this->getDoctrine()->getRepository("AppBundle:Formation")->findOneById($req->get('idform'));

            $applicants = $formation->getApplicants();

            foreach ($applicants as $a){
                if($a->getId()==$this->getUser()->getId()){
                    return new JsonResponse(array('data' => 1));
                }
            }
            return new JsonResponse(array('data' => 0));
        }
        return new Response("Erreur : Ce n'est pas une requête Ajax",400);
    }

    /**
     * @Route("/formationaddapplicant",name="formationapplicant")
     */
    public function addApplicantAction(Request $req){
        if($req->isXmlHttpRequest()) {
            $formation = $this->getDoctrine()->getRepository("AppBundle:Formation")->findOneById($req->get('idform'));
            $formation->addApplicant($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();
        }
            return new JsonResponse(array('data' => 0));
    }
}
