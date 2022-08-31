<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/list', name: 'app_user_list', methods: ['GET'])]
    public function index(UserRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll());
    }

    #[Route('/user/create', name: 'app_user_create', methods: ['POST'])]
    public function create(
        Request        $request,
        UserRepository $repository
    ): JsonResponse
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if (!$form->isSubmitted()||!$form->isValid()) {
            $errors = $form->getErrors(true);
            $msg = [];
            foreach ($errors as $error) {
                $msg[] = $error->getMessage();
            }
            return $this->json(['errors' => $msg], Response::HTTP_BAD_REQUEST);
        }

        $repository->add($user, true);

        return $this->json([],Response::HTTP_CREATED);
    }

    #[Route('/user/update/{email}', name: 'app_user_update', methods: ['PUT'])]
    public function update(
        User            $user,
        ManagerRegistry $doctrine,
        Request         $request
    ): JsonResponse
    {
        $manager = $doctrine->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if (!$form->isSubmitted()||!$form->isValid()) {
            $errors = $form->getErrors(true);
            $msg = [];
            foreach ($errors as $error) {
                $msg[] = $error->getMessage();
            }

            return $this->json(['errors' => $msg], Response::HTTP_BAD_REQUEST);
        }

        $manager->flush();

        return $this->json([]);
    }

    #[Route('/user/delete/{email}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(
        User           $user,
        UserRepository $repository
    ): JsonResponse
    {
        $repository->remove($user, true);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/user/detail/{email}', name: 'app_user_get', methods: ['GET'])]
    public function get(
        User $user
    ): JsonResponse
    {
        return $this->json($user);
    }
}
