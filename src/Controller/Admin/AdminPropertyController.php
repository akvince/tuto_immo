<?php
    namespace App\Controller\Admin;

    use App\Entity\Property;
    use App\Repository\PropertyRepository;
    use App\Form\PropertyType;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Doctrine\ORM\EntityManagerInterface;

    class AdminPropertyController extends AbstractController
    {
        /**
         * @var PropertyRepository
         */
        private $repository;

        public function __construct(PropertyRepository $repository, EntityManagerInterface $em){
            $this->repository = $repository;
            $this->em = $em;
        }

        public function Index()
        {
            $properties = $this->repository->findAll();
            return $this->render('admin/property/index.html.twig', compact('properties'));
        }
        
        public function New(Request $request)
        {
            $property = new Property();
            $form = $this->createForm(PropertyType::class, $property);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $this->em->persist($property);
                $this->em->flush();
                $this->addFlash('success', 'crée avec success');
                return $this->redirectToRoute('admin');
            }
            return $this->render('admin/property/create.html.twig', [
                'property' => $property,
                'form' => $form->createView()
            ]);
        }

        public function Edit(Property $property, Request $request)
        {
            $form = $this->createForm(PropertyType::class, $property);
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()){
                $this->em->flush();
                $this->addFlash('success', 'modifié avec success');
                return $this->redirectToRoute('admin');
            }

            return $this->render('admin/property/edit.html.twig', [
                'property' => $property,
                'form' => $form->createView()
            ]);
        }

        public function Delete(Property $property, Request $request)
        {
            if($this->isCsrfTokenValid('delete'.$property->getId(), $request->get('_token'))){
                $this->em->remove($property);
                $this->em->flush();
                $this->addFlash('success', 'supprimé avec success');
                return $this->redirectToRoute('admin');
            }
        }
    }