<?php


namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Form\WriteReviewFormType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
            [
                "bookById"=>$book,
                "myArray"=> [1,2,3,4,5,6,7,8,9,10,11,12]            
            ]
        );

    }

    /**
     * @Route ("/myAccount", name="myAccount")
     */
    public function myAccount()
    {
        return $this->render(
            "myAccount/base.html.twig",
            ["myArray"=> [1,2,3,4,5,6,7,8,9,10,11,12]]
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
     * @Route ("/myAccount/book/{id}/write", name="writeReview")
     */
    public function writeReview( LoggerInterface $logger, Book $book, Request $request, EntityManagerInterface $doctrine, AuthenticationUtils $authenticationUtils)
    {

        $form = $this->createForm(WriteReviewFormType::class);

        $form->handleRequest($request);

        $user = $this->getUser();
        // $userId = $user->getId();
        $logger->info('Cosa');

        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();
            $review->setCodLibro($book);
            $review->setCodUser($user);

            $doctrine->persist($review);
            $doctrine->flush();

            $this->addFlash('success', "Review created");

            return $this->redirectToRoute("myAccount");
        }

        // $repo = $doctrine->getRepository(Book::class);
        // $book = $repo->find($idBook);

        return $this->render(
            "myAccount/myAccountWriteReview.html.twig",
            [
                "bookById"=>$book,
                'reviewForm'=> $form->createView()
            ]
        );

    }
}