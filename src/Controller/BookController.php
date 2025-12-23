<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;

final class BookController extends AbstractController
{
    #[Route('/externalData', name: 'app_other_api')]
    public function index(HttpClientInterface $httpClient): JsonResponse
    {
        $data = $httpClient->request("GET", "https://www.themealdb.com/api/json/v1/1/random.php");

        return new JsonResponse($data->getContent(), $data->getStatusCode(), [], true);
    }

    #[Route('/api/books', name: 'app_books_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Affiche la liste des livres',
        content: new Model(type: Book::class)
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'Numéro de la page désirée',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'query',
        description: 'Nombre de livres désirée par page',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'Read')]
    public function showBooksList(Request $request, SerializerInterface $serializer, BookRepository $bookRepository): JsonResponse
    {

        $numPage = $request->get('page', 1);
        $limitNbBooks = $request->get('limit', 5);

        $books = $bookRepository->findAllWithPagination($numPage, $limitNbBooks);
        if ($books) {
            $jsonBooks = $serializer->serialize($books, 'json');
            return new JsonResponse($jsonBooks, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/book/{id}', name: 'app_book_details', methods: ['GET'])]
    #[OA\Tag(name: 'Read')]
    public function showBookDetails($id, SerializerInterface $serializer, BookRepository $bookRepository): JsonResponse
    {
        $book = $bookRepository->find($id);

        if ($book) {
            $jsonBookDetail = $serializer->serialize($book, 'json');
            return new JsonResponse($jsonBookDetail, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/books', name: 'app_books_add', methods: ['POST'])]
    #[OA\Tag(name: 'Create')]
    #[OA\Response(
        response: 201,
        description: 'Ajout un nouveau livre',
        content: new Model(type: Book::class)
    )]
    #[OA\RequestBody(
        description: 'Données du livre à ajouter',
        content: new OA\JsonContent(ref: new Model(type: Book::class))
    )]
    public function addNewBook(ValidatorInterface $validator, EntityManagerInterface $em, Request $request, SerializerInterface $serializer, BookRepository $bookRepository): JsonResponse
    {
        $newBook = $serializer->deserialize($request->getContent(), Book::class, 'json');

        // On verifie les erreurs
        $errors = $validator->validate($newBook);

        if($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($newBook);
        $em->flush();

        $jsonNewBook = $serializer->serialize($newBook, 'json');
        return new JsonResponse($jsonNewBook, Response::HTTP_CREATED, [], true);

    }

    #[Route('/api/books/{id}', name: 'app_books_edit', methods: ['PUT'])]
    #[OA\Tag(name: 'Update')]
    public function editBook($id, Book $currentBook, EntityManagerInterface $em, Request $request, SerializerInterface $serializer,  BookRepository $bookRepository): JsonResponse
    {
        // $editBook = $serializer->deserialize(
        //     $request->getContent(), 
        //     Book::class, 
        //     'json', 
        //     [AbstractNormalizer::OBJECT_TO_POPULATE => $currentBook]);

        $content = $request->toArray();

        if($content) {
            $bookToUpdate = $bookRepository->find($id);
            $bookToUpdate->setTitle($content["title"]);
            $bookToUpdate->setAuthor($content["author"]);
        }

        $em->persist($bookToUpdate);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);

    }


    #[Route('/api/books/{id}', name: 'app_books_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Delete')]
    public function deleteBook(Book $book, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($book);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);

    }
}
