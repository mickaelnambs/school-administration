<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Constant\MessageConstant;
use App\Controller\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * CLass AdminAccountController.
 * 
 * @Route("/admin/accounts")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class AdminAccountController extends BaseController
{
    /** @var UserPasswordEncoderInterface */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * AdminAccountController constructor.
     *
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($em);
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Account register.
     * 
     * @Route("/register", name="admin_account_register", methods={"POST","GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            if ($this->save($user)) {
                $this->addFlash(MessageConstant::SUCCESS_TYPE, "User created with success");
            }
            return $this->redirectToRoute('admin_account_login');
        }
        return $this->render('admin/account/registration.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Account Login.
     * 
     * @Route("/login", name="admin_account_login", methods={"POST","GET"})
     *
     * @param AuthenticationUtils $authenticationUtils
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }
        return $this->render('admin/account/login.html.twig', [
            'username' => $authenticationUtils->getLastUsername(),
            'hasError' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * Account Logout.
     * 
     * @Route("/logout", name="admin_account_logout")
     *
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
