<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $books = [
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'summary' => 'Dans une société totalitaire, Big Brother contrôle chaque individu.',
                'genre' => 'Dystopie',
                'publishedDate' => new \DateTime('1949-06-08'),
                'coverImage' => '1984.jpg',
                'available' => true,
            ],
            [
                'title' => 'Le Meilleur des mondes',
                'author' => 'Aldous Huxley',
                'summary' => 'Une société futuriste où le bonheur est imposé.',
                'genre' => 'Science-fiction',
                'publishedDate' => new \DateTime('1932-01-01'),
                'coverImage' => 'meilleur_des_mondes.jpg',
                'available' => true,
            ],
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'summary' => 'La planète Arrakis est au cœur d’un conflit galactique.',
                'genre' => 'Science-fiction',
                'publishedDate' => new \DateTime('1965-08-01'),
                'coverImage' => 'dune.jpg',
                'available' => false,
            ],
            [
                'title' => 'Le Seigneur des Anneaux',
                'author' => 'J.R.R. Tolkien',
                'summary' => 'La quête de l’Anneau Unique pour sauver la Terre du Milieu.',
                'genre' => 'Fantasy',
                'publishedDate' => new \DateTime('1954-07-29'),
                'coverImage' => 'lotr.jpg',
                'available' => true,
            ],
            [
                'title' => 'Harry Potter à l’école des sorciers',
                'author' => 'J.K. Rowling',
                'summary' => 'Un jeune sorcier découvre son destin à Poudlard.',
                'genre' => 'Fantasy',
                'publishedDate' => new \DateTime('1997-06-26'),
                'coverImage' => 'harry_potter_1.jpg',
                'available' => true,
            ],
            [
                'title' => 'L’Étranger',
                'author' => 'Albert Camus',
                'summary' => 'Un homme indifférent au monde est confronté à la justice.',
                'genre' => 'Roman philosophique',
                'publishedDate' => new \DateTime('1942-01-01'),
                'coverImage' => 'letranger.jpg',
                'available' => true,
            ],
            [
                'title' => 'La Peste',
                'author' => 'Albert Camus',
                'summary' => 'Une épidémie frappe la ville d’Oran.',
                'genre' => 'Roman',
                'publishedDate' => new \DateTime('1947-01-01'),
                'coverImage' => 'la_peste.jpg',
                'available' => false,
            ],
            [
                'title' => 'Fahrenheit 451',
                'author' => 'Ray Bradbury',
                'summary' => 'Dans un futur où les livres sont interdits, ils sont brûlés.',
                'genre' => 'Dystopie',
                'publishedDate' => new \DateTime('1953-10-19'),
                'coverImage' => 'fahrenheit_451.jpg',
                'available' => true,
            ],
            [
                'title' => 'Les Misérables',
                'author' => 'Victor Hugo',
                'summary' => 'Le destin de Jean Valjean dans la France du XIXe siècle.',
                'genre' => 'Roman historique',
                'publishedDate' => new \DateTime('1862-01-01'),
                'coverImage' => 'les_miserables.jpg',
                'available' => true,
            ],
            [
                'title' => 'Le Petit Prince',
                'author' => 'Antoine de Saint-Exupéry',
                'summary' => 'Un conte poétique et philosophique sur l’enfance et l’humanité.',
                'genre' => 'Conte philosophique',
                'publishedDate' => new \DateTime('1943-04-06'),
                'coverImage' => 'le_petit_prince.jpg',
                'available' => true,
            ],
        ];

        foreach ($books as $data) {
            $book = new Book();
            $book->setTitle($data['title']);
            $book->setAuthor($data['author']);
            $book->setSummary($data['summary']);
            $book->setGenre($data['genre']);
            $book->setPublishedDate($data['publishedDate']);
            $book->setCoverImage($data['coverImage']);
            $book->setAvailable($data['available']);

            $manager->persist($book);
        }

        $manager->flush();
    }
}
