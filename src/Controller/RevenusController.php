<?php

namespace App\Controller;
use App\Entity\Utulisateur;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Paiement;
use App\Entity\Seance;
use App\Entity\TypeRevenu;
use App\Entity\Affectation;
use App\Entity\Groupe;
use App\Entity\Absence;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\PaiementCommande;
use App\Entity\GroupeAdherent;
use App\Entity\Fournisseur;
use App\Repository\UserRepository;
use App\Repository\TypeRevenuRepository;
use App\Repository\GroupeRepository;
use App\Repository\PaiementRepository;
use App\Repository\AbseanceRepository;
use App\Repository\ProduitRepository;
use App\Repository\FournisseurRepository;
use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\UtulisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Debug\Debug;
use Psr\Log\LoggerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class RevenusController extends AbstractController
{


 
 /**
     * @Route("/ajouter_revenu" , name="ajouter_revenu" )
     */



public function ajouter_revenu(Request $request):Response


    {  
 $entityManager = $this->getDoctrine()->getManager();


        $charge = new TypeRevenu();



         $form = $this->createFormBuilder($charge)
     
              ->add('lib',TextType::class, [
               
                
                'label' => false 
            ]  )

             ->add('montant',IntegerType::class, [
               
                
                'label' => false 
            ]  ) 
           

            ->add('save', SubmitType::class, ['label' => 'Ajouter un revenu'])
            ->getForm();


             $form->handleRequest($request);





 if ($form->isSubmitted() && $form->isValid())

 {    
    $type = $entityManager->getRepository(TypeRevenu::class)->findBy([
    "lib" => $charge->getLib(),
   
]);
    if ($type) {
      return   $this->render('revenu.html.twig', [
            'form' => $form->createView(),
            'error'=>'deja existe'
        ]);
    }

     $charge->setLib($charge->getLib());
     $charge->setMontant($charge->getMontant());

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($charge);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return   $this->redirectToRoute('list_revenu');
 }







            return   $this->render('revenu.html.twig', [
            'form' => $form->createView(),
            'error'=>''
        ]);
        
    }  

     /**
     * @Route("/modifier_revenu/{id}" , name="modifier_revenu" )
     */



public function modifier_revenu(Request $request,$id):Response


    {  
 $entityManager = $this->getDoctrine()->getManager();

$charge =$entityManager->getRepository(TypeRevenu::class)->find($id);
        $charge1 = new TypeRevenu();



         $form = $this->createFormBuilder($charge1)
     
              ->add('lib',TextType::class, [
               
                
                'label' => false ,
                'data'=>$charge->getLib()
            ]  )

             ->add('montant',IntegerType::class, [
               
                
                'label' => false ,
                'data'=>$charge->getMontant()
            ]  ) 
           

            ->add('save', SubmitType::class, ['label' => 'modifier un revenu'])
            ->getForm();


             $form->handleRequest($request);





 if ($form->isSubmitted() && $form->isValid())

 {    
   $type = $entityManager->getRepository(TypeRevenu::class)->findBy([
    "lib" => $charge->getLib(),
   
]);
   $type1=$type[0];
    if ($type1 && $type1->getId()!= $charge->getId()) {
      return   $this->render('revenu.html.twig', [
            'form' => $form->createView(),
            'error'=>'deja existe'
        ]);
    }
   

     $charge->setLib($charge1->getLib());
     $charge->setMontant($charge1->getMontant());

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
      

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
         return   $this->redirectToRoute('list_revenu');
 }







            return   $this->render('revenu.html.twig', [
            'form' => $form->createView(),
            'error'=>''
        ]);
        
    }  


     /**
     * @Route("/delete_revenu/{id}" , name="delete_revenu" )
     */



public function delete_revenu($id):Response


    {  $this->denyAccessUnlessGranted('ROLE_ADMIN');
       $entityManager=  $this->getDoctrine()->getManager();
       $charge =$entityManager->getRepository(TypeRevenu::class)->find($id);
       $entityManager->remove($charge);
       $entityManager->flush();


      /*$Task= $var->find($id);

      return $this->render('home1.html.twig' ,[
            'form' => $Task->getImage() ]);
*/

            return   $this->redirectToRoute('list_revenu');
        
    }  



      /**
     * @Route("/list_revenu" , name="list_revenu" )
     */



public function list_revenu(TypeRevenuRepository $var):Response

{
       $revenus= $var->findAll();

      return $this->render('list_revenu.html.twig' ,[
            'revenus' => $revenus ]);
        
    }



}


?>