<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\SerializerInterface;

use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;


final class UserController extends AbstractController
{
    #[Route('/api/userList', name: 'app_user_list', methods: ['GET'])]
    #[OA\Tag(name: 'Read')]
    public function showUsersList(SerializerInterface $serializer, UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        if ($users) {
            $jsonUsers = $serializer->serialize($users, 'json', ['groups'=> 'getUser']);
            return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
