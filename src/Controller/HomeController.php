<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[Route('/search', name: 'app_home_search', methods: ['POST'])]
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        // cas de recherche :

        if($request->getData('_route') === 'app_home_search'){

            $searchValue = $request->get('value');
            if($searchValue == ""){
                return $this->redirectToRoute('app_home');
            }

            $products = $productRepository->findBy([
                'description'=>$searchValue
            ]);



        }



        $products = $productRepository->findAll();

        return $this->render('home/index.html.twig',
        [
            'products'=>$products
        ]);
    }

    #[Route('testmail', name:'test_mail')]
    public function testmail(MailerInterface $mailer, Pdf $pdfmaker, Environment $twig)
    {

       // bundle pour PDF :
        // https://github.com/KnpLabs/KnpSnappyBundle

        //google : authentification en 2 étapes et créer un mot
        //de passe d'application à entrer dans le .env

        $html = $twig->render('home/truc.html.twig',[
            'mesVariables'=> "ma valeur"
        ]);
        $pdf = $pdfmaker->getOutputFromHtml($html);

        // pour créer le fichier et le stocker dans public
       // $pdfmaker->generateFromHtml($html,"tructruc3.pdf");



        $email = (new Email())
            ->from('contact@imatrythis.com')

            //tester avec votre propre email
            ->to('shoppymcshop9@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Le Test bla bla')
            ->text('le test le test')



            //pour attacher le PDF au mail
            ->attach($pdf, sprintf('facture.pdf'));

        $mailer->send($email);
       // dd($email);

        return $this->redirectToRoute('app_home');
    }
}
