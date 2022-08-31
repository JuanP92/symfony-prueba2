<?php

namespace App\Controller;

use App\Document\Order;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Form\OrderType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/list', name: 'app_order_list')]
    public function index(DocumentManager $manager): JsonResponse
    {
        $repository = $manager->getRepository(Order::class);
        $data = $repository->findAll();

        return $this->json($data);
    }

    #[Route('/order/create', name: 'app_order_create', methods: ['POST'])]
    public function create(
        Request         $request,
        DocumentManager $manager,
        UserRepository  $userRepository
    ): JsonResponse
    {
        $data = $request->toArray();
        $user = $userRepository->findOneBy($data['userEmail']);
        if (!$user) {
            $user = new User();
            $userData = $data['user'];
            $form = $this->createForm(UserType::class, $user);
            $form->submit($userData);
            if (!$form->isValid()) {
                $errors = $form->getErrors(true);
                $msg = [];
                foreach ($errors as $error) {
                    $msg[] = $error->getMessage();
                }

                return $this->json(['errors' => $msg], 400);
            }

            $userRepository->add($user, true);
        }

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->submit($data);
        if (!$form->isValid()) {
            $errors = $form->getErrors(true);
            $msg = [];
            foreach ($errors as $error) {
                $msg[] = $error->getMessage();
            }

            return $this->json(['errors' => $msg], 400);
        }

        $manager->persist($order);
        $manager->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/order/details/{email}', name: 'app_order_details', methods: ['GET'])]
    public function list(
        User            $user,
        DocumentManager $manager
    ): JsonResponse
    {
        if (!$user) {
            return $this->json([
                'message' => 'User not found'], 404);
        }

        $repository = $manager->getRepository(Order::class);
        $orders = $repository->findby(['userEmail' => $user->getEmail()]);

        return $this->json($orders);
    }

    #[Route('/order/update/{id}', name: 'app_order_update', methods: ['PUT'])]
    public function update(
        string          $id,
        Request         $request,
        DocumentManager $manager,
        UserRepository  $userRepository
    ): JsonResponse
    {
        $order = $manager->getRepository(Order::class)->find($id);
        $data = $request->toArray();
        if (!$order) {
            return $this->json([
                'message' => 'Order not found'], 404);
        }

        $form = $this->createForm(OrderType::class, $order);
        $form->submit($data);
        if (!$form->isValid()) {
            $errors = $form->getErrors(true);
            $msg = [];
            foreach ($errors as $error) {
                $msg[] = $error->getMessage();
            }
            return $this->json(['errors' => $msg], 400);
        }

        $manager->flush();

        return $this->json([
            'success' => true,
            'order' => $order
        ]);
    }

    #[Route('/order/delete/{email}', name: 'app_order_delete', methods: ['DELETE'])]
    public function delete(
        string          $email,
        DocumentManager $manager
    ): JsonResponse
    {

        $orders = $manager->getRepository(Order::class)
            ->findby(['userEmail' => $email]);
        if (!$orders) {
            return $this->json([
                'message' => 'Orders not found'], 404);
        }

        $manager->createQueryBuilder(Order::class)
            ->remove()
            ->field("userEmail")
            ->equals($email)
            ->getQuery()
            ->execute();

        return $this->json([
            'success' => true
        ]);
    }
}
