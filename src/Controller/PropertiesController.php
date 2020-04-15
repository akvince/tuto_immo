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
    use App\Form\IntergerType;

    class PropertiesController extends AbstractController
    {

        public function __construct(EntityManagerInterface $em)
        {
            $this->em = $em;
        }
        
        public function Index(PropertyRepository $repository, PaginatorInterface $paginator, Request $request):Response
        {
            $search = new PropertySearch();
            $form = $this->createForm(PropertySearchType::class, $search);
            $form->handleRequest($request);
            $propertiesQuery = $repository->findNotSoldQuery($search);
            $properties = $paginator->paginate(
                $propertiesQuery,
                $request->query->getInt('page', 1),
                8
            );

            return $this->render('properties/properties.html.twig', [
                'properties' => $properties,
                'current_menu' => 'properties',
                'form' => $form->createView()
            ]);
        }
    }