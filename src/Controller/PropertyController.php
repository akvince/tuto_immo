<?php
    namespace App\Controller;

    use App\Entity\Property;
    use App\Repository\PropertyRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Doctrine\ORM\EntityManagerInterface;

    class PropertyController extends AbstractController
    {

        public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
        {
            $this->repository = $repository;
            $this->em = $em;
        }


        public function Index(Property $property, string $slug):response
        {
            if($property->getSlug() !== $slug){
                return $this->redirectToRoute('property',[
                    'id' => $property->getId(),
                    'slug' => $property->getSlug()
                ], 301);
            }
            return $this->render('property/property.html.twig', [
                'property' => $property
            ]);
        }
    }