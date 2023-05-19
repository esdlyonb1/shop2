<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('home/index.html.twig',
        [
            'products'=>$productRepository->findAll()
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
            ->from()

            //tester avec votre propre email
            ->to('destinataire@mail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')



            //pour attacher le PDF au mail
            ->attach($pdf, sprintf('facture.pdf'));

        $mailer->send($email);
       // dd($email);

        return $this->redirectToRoute('app_home');
    }
}
