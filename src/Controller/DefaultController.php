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
        $loremUsers = ['pablo','m_martins','first_user','another','lorem_user','lorem132','user41','peter468'];

        return $this->render(
            "bookDetail.html.twig",
            [
                "bookById"=>$book,
                "loremUsers"=>$loremUsers            
            ]
        );

    }

    /**
     * @Route ("/myAccount", name="myAccount")
     */
    public function myAccount(EntityManagerInterface $doctrine, LoggerInterface $logger)
    {
        // $repo = $doctrine->getRepository(User::class);
        // $books = $repo->findAll();

        $logger->info('Cosa');

        $user = $this->getUser();
        $reviews = $user->getReviews();
        // dump($reviews);
        // arsort($reviews);
        $amountReviews = count($reviews);
        // $codLibro = $reviews->getCodLibro();

        $repo = $doctrine->getRepository(Book::class);
        $loremBooks = $repo->findAll();

        return $this->render(
            "myAccount/base.html.twig",
            [
                "reviews"=> $reviews,
                "amountReviews"=>$amountReviews,
                "loremBooks"=>$loremBooks
            ]
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