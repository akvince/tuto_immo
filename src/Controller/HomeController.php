<?php

    namespace App\Controller;

    use App\Repository\PropertyRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;

    class HomeController extends AbstractController
    {
        /**
         * @param PropertyRepository $repository
         * @return Response
         */
        public function index(PropertyRepository $repository):Response
        {
            $properties = $repository->findLatest();
            return $this->render('home.html.twig', compact('properties'));
        }
    }