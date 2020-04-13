<?php
    namespace App\Controller;

    use App\Entity\Property;
    use App\Repository\PropertyRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Doctrine\ORM\EntityManagerInterface;
    use Knp\Component\Pager\PaginatorInterface;
    use App\Entity\PropertySearch;
    use App\Form\PropertySearchType;

    class PropertiesController extends AbstractController
    {

        public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
        {
            $this->propertiesQuery = $repository->findNotSoldQuery();
            $this->properties = $repository->findNotSold();
            $this->em = $em;
        }

        public function Index(PaginatorInterface $paginator, Request $request):Response
        {
            

            $properties = $paginator->paginate(
                $this->propertiesQuery,
                $request->query->getInt('page', 1),
                8
            );

            return $this->render('properties/properties.html.twig', [
                'properties' => $properties,
                'current_menu' => 'properties'
            ]);
        }
    }