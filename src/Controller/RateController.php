<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Rate;
use App\Repository\RateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RateController extends AbstractController
{
    #[Route('/rate/{id}/mark/{mark}', name: 'app_rate')]
    public function index(Product $product, RateRepository $rateRepository, $mark = null, EntityManagerInterface $manager): Response
    {
      $user = $this->getUser();




      if(!$user || !$product){



          $this->addFlash('danger', 'failed rating attempt');
          return $this->redirectToRoute('app_home');

      }
      if(!ctype_digit($mark)){

          $this->addFlash('danger', 'failed rating attempt');

          return $this->redirectToRoute('app_home');

      }

      if($mark < 0 || $mark > 5){

          $this->addFlash('danger', 'failed rating attempt');

          return $this->redirectToRoute('app_home');

      }

        //checker l'existence d'un rate

            $rate = $rateRepository->findOneBy([
                'author'=>$user,
                'product'=>$product
            ]);

            if(!$rate){
                $rate = new Rate();
                $rate->setAuthor($user);
                $rate->setProduct($product);
            }

            $rate->setMark($mark);
            $manager->persist($rate);
            $manager->flush();


        return $this->redirectToRoute('app_product_show', ['id'=>$product->getId()]);

    }
}
