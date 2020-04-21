<?php
    namespace App\Controller;

    use App\Entity\Property;
    use App\Entity\Contact;
    use App\Repository\PropertyRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Doctrine\ORM\EntityManagerInterface;
    use App\Form\ContactType;
    use App\Notification\ContactNotification;

    class PropertyController extends AbstractController
    {

        public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
        {
            $this->repository = $repository;
            $this->em = $em;
        }


        public function Index(Property $property, string $slug, Request $request, ContactNotification $notification): Response
        {
            
            if($property->getSlug() !== $slug){
                return $this->redirectToRoute('property',[
                    'id' => $property->getId(),
                    'slug' => $property->getSlug()
                ], 301);
            }
            
            $contact = new Contact();
            $contact->setProperty($property);
            $form = $this->createForm(ContactType::class, $contact);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $notification->notify($contact);
                $this->addFlash('success', 'Votre email a bien été envoyé');
                return $this->redirectToRoute('property',[
                    'id' => $property->getId(),
                    'slug' => $property->getSlug()
                ]);
            }

            return $this->render('property/property.html.twig', [
                'property' => $property,
                'form' => $form->createView()
            ]);
        }
    }