<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function listAction()
    {
        return $this->render('user/list.html.twig', ['users' => $this->em->getRepository(User::class)->findAll()]);
    }


    public function createAction(Request $request, UserPasswordHasherInterface $userPwdHasherInt)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*$em = $this->getDoctrine()->getManager();*/
            /*$password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);*/
            $plainPwd = $user->getPassword();
            $user->setPassword(
                $userPwdHasherInt->hashPassword(
                    $user,
                    $plainPwd
                )
            );

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }


    public function editAction(User $user, Request $request, UserPasswordHasherInterface $userPwdHasherInt)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPwd = $user->getPassword();
            $user->setPassword(
                $userPwdHasherInt->hashPassword(
                    $user,
                    $plainPwd
                )
            );
            $this->em->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
