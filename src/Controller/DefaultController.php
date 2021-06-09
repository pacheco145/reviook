<?php


namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route ("/", name="home")
     */
    public function home(EntityManagerInterface $doctrine)
    {
        // $dataBooks = file_get_contents('../books.json');
        // $books = json_decode($dataBooks, true);

        $repo = $doctrine->getRepository(Book::class);
        $books = $repo->findAll();

        // return new Response($books);

        return $this->render(
            "home.html.twig",
            ["homeBooks"=>$books]
        );
    }

    /**
     * @Route ("/book/{idBook}", name="bookDetail")
     */
    public function bookDetail($idBook, EntityManagerInterface $doctrine)
    {
        // $dataBooks = file_get_contents('../books.json');
        // $books = json_decode($dataBooks, true);

        $repo = $doctrine->getRepository(Book::class);
        $book = $repo->find($idBook);

        return $this->render(
            "bookDetail.html.twig",
            ["bookById"=>$book]
        );

    }

    /**
     * @Route ("/myAccount/search", name="searchBook")
     */
    public function searchBook()
    {
        return $this->render(
            "myAccount/myAccountSearchIsbn.html.twig"
        );

    }

    /**
     * @Route ("/myAccount/write", name="writeReview")
     */
    public function writeReview()
    {
        return $this->render(
            "myAccount/myAccountWriteReview.html.twig"
        );

    }
}