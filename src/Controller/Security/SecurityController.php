<?php
    namespace App\Controller\Security;

    use App\Entity\User;
    use App\Form\UserType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

    class SecurityController extends AbstractController {
        
        public function __construct(UserPasswordEncoderInterface $encoder)
        {
            $this->encoder = $encoder;
        }

        public function Login(AuthenticationUtils $authenticationUtils)
        {
            $form = $this->createForm(UserType::class);
            $lastUsername = $authenticationUtils->getLastUsername();
            $error = $authenticationUtils->getLastAuthenticationError();

            return $this->render('admin/login/login.html.twig', [
                'form' => $form->createView(),
                'last_username' => $lastUsername,
                'error' => $error
            ]);
        }
        
        public function Signup(Request $request, EntityManagerInterface $em)
        {
            $form = $this->createForm(UserType::class);

            if ($request->isMethod('POST')){
                $data = $request->request->get('user');
                $user = new User();
                // dd($data);
                $user->setUsername($data['username']);
                $user->setPassword($this->encoder->encodePassword($user, $data['password']));
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('login');
            }

            return $this->render('admin/signup/signup.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }