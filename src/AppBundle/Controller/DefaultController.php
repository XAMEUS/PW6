<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Formation;
use AppBundle\Entity\Role;

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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
        ->add('sex', ChoiceType::class, array(
            'choices'  => array(
                'M' => "M",
                'F' => "F",
            ),
            'expanded' => true,
            'label' => "Sex"))
        ->add('entry', DateType::class, array('label' => 'Entry date'))
        ->add('contract', ChoiceType::class, array(
            'choices'  => array(
                'CDD' => "CDD",
                'CDI' => "CDI",
            ),
            'expanded' => true,
            'label' => "Contract type"))
        ->add('duration', IntegerType::class, array('label' => "Contract duration (month)"))
        ->add('salary', IntegerType::class, array('label' => "Salary"))
        ->add('superior', IntegerType::class, array('label' => "Superior (id)"))
        ->add('roles', ChoiceType::class, array(
            'choices'  => array(
                'ROLE_ADMIN' => new Role("ROLE_ADMIN"),
                'ROLE_USER' => new Role("ROLE_USER"),
                'ROLE_FORMATIONVALIDATOR' => new Role("ROLE_FORMATIONVALIDATOR"),
            ),
            'multiple' => true,
            'expanded' => true,
            'label' => "Roles"
        ))
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
        return $this->render('directory.html.twig', [

        ]);
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
     * @Route("/spreadsheet", name="spreadsheet")
     */
    public function spreadsheetShow(Request $request)
    {
        $columns = $request->request->get("columns");
        $rows = $request->request->get("rows");
        if ($columns == null) $columns = 20;
        if ($rows == null) $rows = 20;
        return $this->render('calc.html.twig', [
            'columns' => $columns,
            'rows' => $rows,
        ]);
    }
}
