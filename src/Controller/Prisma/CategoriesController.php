<?php

namespace App\Controller\Prisma;

use App\Enum\Prisma;
use App\Entity\Categories;
use App\Entity\CategoryDescription;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Persistence\ManagerRegistry;

class CategoriesController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/prisma/categories', name: 'app_prisma_categories')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {

        $entityManager = $doctrine->getManager();
        $prismaCategories = $this->getCategories();

        foreach($prismaCategories['GroupsListItems'] as $prismaCategory){

            $id = $prismaCategory['Id'];
            $parent_id = (int) $prismaCategory['GroupId'];
            $code = $prismaCategory['Code'];
            $title = $prismaCategory['Description'];
            $status = $prismaCategory['Disable'] == 'True' ? 0 : 1;
            $sort = $prismaCategory['Sort'];
            $eshop_status = $prismaCategory['eShop'] == 'True' ? 1 : 0;


            $category = new \App\Entity\Categories;
            $categoryDescription = new \App\Entity\CategoryDescription;
            
            $exist_category = $doctrine->getRepository(\App\Entity\Categories::class)->findOneBy(['category_id' => $id]);

            if(!$exist_category){

                $categoryDescription->setName($title)
                                    ->setLanguageId(2);
                
                $category->setCategoryId($id)
                    ->setParentId($parent_id)
                    ->setCategoryCode($code)
                    ->addCategoryDescription($categoryDescription)
                    ->setStatus($status)
                    ->setOrderSort($sort)
                    ->setEshopStatus($eshop_status)
                    ->setDateAdded(new \DateTime())
                    ->setDateModified(new \DateTime());

                    $entityManager->persist($category);
                    $entityManager->persist($categoryDescription);
            }else{

                $exist_category->setParentId($parent_id)
                ->setCategoryCode($code)
                ->setStatus($status)
                ->setOrderSort($sort)
                ->setEshopStatus($eshop_status)
                ->setDateModified(new \DateTime())
                ->getCategoryDescription()
                ->get(0)
                ->setName($title)
                ->setLanguageId(2);
            }
        }

        $entityManager->flush();

        return $this->render('prisma/categories/index.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }

    public function getCategories(){
        $response = $this->client->request('POST', Prisma::$URL .'/'. Prisma::$GET_CATEGORIES, [
            'body' => ['SiteKey' => Prisma::$SITE_KEY],
        ]); 

        return json_decode(json_encode((array)simplexml_load_string($response->getContent())),true);
    }
}
