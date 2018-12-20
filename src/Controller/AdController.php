<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/annonces", name="ads_list")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * Return a response : ad
     * @Route("/annonces/{slug}", name="ads_show")
     */
    public function show(Ad $ad)
    {
        // on utilise le paramconverter
        //$ad = $adRepository->findOneBySlug($slug);

        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * create new add - form
     * @Route("/annonces/créer", name="ads_create")
     */
    public function create()
    {
        return $this->render('ad/new.html.twig');
    }
}
