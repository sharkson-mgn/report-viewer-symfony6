<?php

namespace App\Controller;

use App\Entity\Exports;
use App\Repository\ExportsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        

        return $this->render('pages/main.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/api/locals', methods: ['GET'])]
    public function getLocals(ManagerRegistry $doctrine): Response
    {
        $find = new ExportsRepository($doctrine);
        return $this->json($find->getLocals());
    }

    #[Route('/api/place/{placeName<[0-9a-zA-Z_]+>}/{from<\d+>}/{to<\d+>}/', name: 'app_api_filter', methods: ['GET'])]
    public function apiFilter(string $placeName = null, int $from = 0, int $to = null, ManagerRegistry $doctrine): Response {

        $to = (!$to) ? time() : $to;

        $find = new ExportsRepository($doctrine);

        $result = $find->findExports($placeName,$from,$to);
        
        return $this->json($result);

    }

    #[Route('/api/inserttest')]
    public function insertTest(ManagerRegistry $doctrine): Response {

        $entityManager = $doctrine->getManager();

        $export = new Exports();

        $export->setName($this->generateRandomString(15));

        $date = new \DateTimeImmutable();
        $someDate = $date->modify('-'.random_int(1, 30).' day');
        $export->setExportAt($someDate);

        $export->setExportUser($this->generateRandomString(15));

        $export->setLocalName(random_int(1, 2) == 2 ? $this->generateRandomString(15) : 'test');

        $entityManager->persist($export);

        $entityManager->flush();

        return new Response('Saved new example with id '.$export->getId());

    }

    public function generateRandomString($length = 10): string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


}
