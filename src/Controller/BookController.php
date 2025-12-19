<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\BookRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class BookController extends AbstractController
{
    #[Route('/externalData', name: 'app_other_api')]
    public function index(HttpClientInterface $httpClient): JsonResponse
    {
        $data = $httpClient->request("GET", "https://www.themealdb.com/api/json/v1/1/random.php");

        return new JsonResponse($data->getContent(), $data->getStatusCode(), [], true);
    }

    #[Route('/api/bookslist', name: 'app_books_list')]
    public function showBooksList(SerializerInterface $serializer, BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAll();

        if ($books) {
            $jsonBooks = $serializer->serialize($books, 'json');
            return new JsonResponse($jsonBooks, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }


    #[Route('/api/book/{id}', name: 'app_book_details')]
    public function showBookDetails($id, SerializerInterface $serializer, BookRepository $bookRepository): JsonResponse
    {
        $book = $bookRepository->find($id);

        if ($book) {
            $jsonBookDetail = $serializer->serialize($book, 'json');
            return new JsonResponse($jsonBookDetail, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
