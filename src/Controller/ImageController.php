<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;

use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/image')]
class ImageController extends AbstractController
{
    #[Route('/{id}', name: 'app_image_index')]
    public function index(Product $product): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);

        return $this->renderForm('image/index.html.twig', [
            'product' => $product,
            'form' => $form
        ]);
    }

    #[Route('addtoproduct/{id}', name: 'app_image_product_add')]
    public function addToProduct(Product $product, Request $request, EntityManagerInterface $manager): Response
    {

        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $image->setProduct($product);
            $manager->persist($image);
            $manager->flush();


        }

        return $this->redirectToRoute('app_image_index', ['id' => $product->getId()], Response::HTTP_SEE_OTHER);


    }

    #[Route('removefromproduct/{id}', name: 'app_image_delete')]
    public function removeFromProduct(Image $image, EntityManagerInterface $manager): Response
    {


        if ($image) {

            $product = $image->getProduct();
            $manager->remove($image);
            $manager->flush();
        }


        return $this->redirectToRoute('app_image_index', ['id' => $product->getId()], Response::HTTP_SEE_OTHER);


    }

}
