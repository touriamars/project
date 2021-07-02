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


class SeanceController extends AbstractController
{
    /**
     * @Route("/ajouter_seance" , name="ajouter_seance" )
     */



public function ajouter_seance(Request $request,GroupeRepository $var):Response


    { 
     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 
 $entityManager = $this->getDoctrine()->getManager();

        $groupes=$var->findAll();
        $seance = new Seance();

$choices = [];
foreach ($groupes as $choice) {
  $choices[$choice->getLib()] =$choice->getId();
}


         $form = $this->createFormBuilder($seance)
            

             ->add('date',DateType::class, [
                  'label' => false ,
                  'widget' => 'single_text',
    // this is actually the default format for single_text
    'format' => 'yyyy-MM-dd',
    'required'=>true
            ]  ) 
             ->add('piscine',ChoiceType::class, [
                'choices' => [
                    'piscine 1' => '1',
                    'piscine 2'  => '2',
                   
                ],
                  'label' => false 
            ]  )
            
             ->add('groupe',ChoiceType::class, [
                'choices' => $choices,
      
                
                'label' => false ,
                'required'=>true
            ]  )
                   ->add('heure_debut',TimeType::class, [
               
       
                
                'label' => 'heure debut' ,
                'required'=>true

            ]  )
                   ->add('heure_fin',TimeType::class, [
               
       
                
                'label' => 'heure fin' ,
                'required'=>true
            ]  )
                   ->add('enseignant',TextType::class, [
               
       
                
                'label' => 'enseignant' ,
                'required'=>true
            ]  )
                    ->add('objectif',TextType::class, [
               
       
                
                'label' => "Intitulé d'objectif"
            ]  )
                     ->add('autre_info',TextType::class, [
               
       
                
                'label' => 'Autres info Objectif' ,
                'required'=>true
            ]  )
                      ->add('objectif_suivant',TextType::class, [
               
       
                
                'label' => 'Objectif suivant' ,
                'required'=>true
            ]  )
                       ->add('observation',TextType::class, [
               
       
                
                'label' => 'Observation' ,
                'required'=>true
            ]  )
                         ->add('code_sequence',TextType::class, [
               
       
                
                'label' => 'Code de Sequence' ,
                'required'=>true

            ]  )
                        -> add('etat', ChoiceType::class, [
    'choices'  => [
        'Objectif atteint' => 'oui',

      
        
    ],
    'multiple'=>true,
    'expanded' => true,
    'label'=>false,
    'mapped'=>false
])
                        -> add('type', ChoiceType::class, [
    'choices'  => [
        'Pratique' => 'pratique',
         'Theorique' => 'theorique',
        
      
        
    ],
    
    'label'=>'code type de seance',
     'required'=>true
])

            ->add('save', SubmitType::class, ['label' => 'Ajouter un seance'])
            ->getForm();


             $form->handleRequest($request);





 if ($form->isSubmitted() && $form->isValid())

 {
   if ($seance->getHeureFin()<= $seance->getHeureDebut()) {
       return   $this->render('ajouter_seance.html.twig', [
            'form' => $form->createView(),
            'error'=>'le delai invalaid'
        ]);
   }

    $entityManager = $this->getDoctrine()->getManager();
    $seances = $entityManager->getRepository(Seance::class)->findAll();

    foreach ($seances  as  $value) {
       if ($value->getDate()==$seance->getdate() and $value->getPiscine() == $seance->getPiscine() and( ($value->getHeureDebut() <= $seance->getHeureDebut() and $seance->getHeureDebut() <= $value->getHeureFin()) or ($value->getHeureDebut() <= $seance->getHeureFin() and $seance->getHeureFin() <= $value->getHeureFin() ) or ( $value->getHeureDebut() >= $seance->getHeureDebut() and $seance->getHeureFin() >= $value->getHeureFin()) ) ) {
          return   $this->render('ajouter_seance.html.twig', [
            'form' => $form->createView(),
            'error'=>'le delai invalaid'
        ]);

          
       }
    }

    
     $jours=date('w',strtotime($seance->getDate()->format('Y-m-d H:i:s')));
     switch ($jours) {
  case "0":
    $jour='dimanche';
    break;
  case "1":
    $jour='lundi';
    break;
  case "2":
  $jour='mardi';
    
    break;
    case "3":
    $jour='mercredi';
    break;
    case "4":
   $jour='jeudi';
    break;
    case "5":
   $jour='vendredi';
    break;
    case "6":
   $jour='samedi';
    break;
  
}
         $seance->setHeureDebut($seance->getHeureDebut());
          $seance->setHeureFin($seance->getHeureFin());
          $seance->setPiscine($seance->getPiscine());
        
          $seance->setJour($jour);
          $seance->setdate($seance->getDate());
          $seance->setType($seance->getType());
            
          /*  foreach ($request->get('etat') as $value) {
              if ($value=='oui') {
                   $seance->setEtat('oui');
              }else{
                $seance->setEtat('non');

              }
               
            }*/
            if ($request->get('etat')) {
                $seance->setEtat('oui');
            }
            else{
                $seance->setEtat('non');

              }
        
          $seance->setEnseignant($seance->getEnseignant());
          $seance->setObjectif($seance->getObjectif());
          $seance->setObjectifSuivant($seance->getObjectifSuivant());
          $seance->setAutreInfo($seance->getAutreInfo());
          $seance->setObservation($seance->getObservation());
          $seance->setCodeSequence($seance->getCodeSequence());
         
          $entityManager->persist($seance);

             $entityManager->flush();

        return   $this->redirectToRoute('list_seance');

    }

  






            return   $this->render('ajouter_seance.html.twig', [
            'form' => $form->createView(),
                'error'=>''
        ]);
        
    }  



      /**
     * @Route("/list_seance" , name="list_seance" )
     */



public function list_seance(SeanceRepository $var):Response

{
   $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
       $seances= $var->findAll();

      return $this->render('list_seance.html.twig' ,[
            'seances' => $seances ]);
        
    }




    /**
     * @Route("/delete_seance/{id}", name="delete_seance")
     */
    public function delete_seance(int $id): Response
    {
       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $seance = $entityManager->getRepository(Seance::class)->find($id);
           $entityManager->remove($seance);
$entityManager->flush();

       

        
        return $this->redirectToRoute('list_seance');
    }





/**
     * @Route("/update_seance/{id}" , name="update_seance" )
     */







    public function update_seance($id,Request $request,GroupeRepository $var)


    {    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

      $entityManager = $this->getDoctrine()->getManager();

     
          $seance1= $entityManager->getRepository(Seance::class)->find($id);
        $groupes=$var->findAll();
        $seance = new Seance();

$choices = [];
foreach ($groupes as $choice) {
  $choices[$choice->getLib()] =$choice->getId();
}


         $form = $this->createFormBuilder($seance)
            

             ->add('date',DateType::class, [
                  'label' => false ,
                    'widget' => 'single_text',
    // this is actually the default format for single_text
    'format' => 'yyyy-MM-dd',
    'required'=>true,
    'data'=>$seance1->getDate(),
            ]  ) 
             ->add('piscine',ChoiceType::class, [
                'choices' => [
                    'piscine 1' => '1',
                    'piscine 2'  => '2',
                   
                ],
                  'label' => false ,
                  'data'=>$seance1->getPiscine(),
            ]  )
            
             ->add('groupe',ChoiceType::class, [
                'choices' => $choices,
      
                
                'label' => false 
            ]  )
                   ->add('heure_debut',TimeType::class, [
               
       
                
                'label' => 'heure debut' ,
                'data'=>$seance1->getHeureDebut(),
            ]  )
                   ->add('heure_fin',TimeType::class, [
               
       
                
                'label' => 'heure fin' ,
                'data'=>$seance1->getHeureFin(),
            ]  )
                   ->add('enseignant',TextType::class, [
               
       
                
                'label' => 'enseignant' ,
                'data'=>$seance1->getEnseignant(),
            ]  )
                        ->add('objectif',TextType::class, [
               
       
                
                'label' => "Intitulé d'objectif",
                'data'=>$seance1->getObjectif(),
            ]  )
                     ->add('autre_info',TextType::class, [
               
       
                
                'label' => 'Autres info Objectif' ,
                'data'=>$seance1->getAutreInfo(),
            ]  )
                      ->add('objectif_suivant',TextType::class, [
               
       
                
                'label' => 'Objectif suivant',
                'data'=>$seance1->getObjectifSuivant(), 
            ]  )
                       ->add('observation',TextType::class, [
               
       
                
                'label' => 'Observation' ,
                'data'=>$seance1->getObservation(),
            ]  )
                         ->add('code_sequence',TextType::class, [
               
       
                
                'label' => 'Code de Sequence' ,
                'data'=>$seance1->getCodeSequence(),
            ]  )
                        -> add('etat', ChoiceType::class, [
    'choices'  => [
        'Objectif atteint' => 'oui',
      
        
    ],

    'multiple'=>true,
    'expanded' => true,
    'label'=>false,
    'mapped'=>false
])
                        -> add('type', ChoiceType::class, [
    'choices'  => [
        'Pratique' => 'pratique',
         'Theorique' => 'theorique',
      
        
    ],
    'data'=>$seance1->getType(),
    
    'label'=>'code type de seance'
])

            ->add('save', SubmitType::class, ['label' => 'modifier un seance'])
            ->getForm();


             $form->handleRequest($request);




 if ($form->isSubmitted() && $form->isValid())

 {
   if ($seance->getHeureFin()<= $seance->getHeureDebut()) {
       return   $this->render('update_seance.html.twig', [
            'form' => $form->createView(),
            'error'=>'le delai invalaid'
        ]);
   }
  $entityManager = $this->getDoctrine()->getManager();
        $seance1= $entityManager->getRepository(Seance::class)->find($id);

     $entityManager = $this->getDoctrine()->getManager();
    $seances = $entityManager->getRepository(Seance::class)->findAll();

    foreach ($seances  as  $value) {
       if ($value->getDate()==$seance->getdate() and $value->getPiscine() == $seance->getPiscine() and( ($value->getHeureDebut() <= $seance->getHeureDebut() and $seance->getHeureDebut() <= $value->getHeureFin()) or ($value->getHeureDebut() <= $seance->getHeureFin() and $seance->getHeureFin() <= $value->getHeureFin() ) or ( $value->getHeureDebut() >= $seance->getHeureDebut() and $seance->getHeureFin() >= $value->getHeureFin()) ) and $seance1->getId()!=$id ) {
          return   $this->render('update_seance.html.twig', [
            'form' => $form->createView(),
            'error'=>'le delai invalaid'
        ]);

          
       }
    }


     $jours=date('w',strtotime($seance->getDate()->format('Y-m-d H:i:s')));
     switch ($jours) {
  case "0":
    $jour='dimanche';
    break;
  case "1":
    $jour='lundi';
    break;
  case "2":
  $jour='mardi';
    
    break;
    case "3":
    $jour='mercredi';
    break;
    case "4":
   $jour='jeudi';
    break;
    case "5":
   $jour='vendredi';
    break;
    case "6":
   $jour='samedi';
    break;
  
}
         $seance1->setHeureDebut($seance->getHeureDebut());
          $seance1->setHeureFin($seance->getHeureFin());
          $seance->setPiscine($seance->getPiscine());
        
          $seance1->setJour($jour);
          $seance1->setdate($seance->getDate());
            $seance->setType($seance->getType());
            
          /*  foreach ($request->get('etat') as $value) {
              if ($value=='oui') {
                   $seance->setEtat('oui');
              }else{
                $seance->setEtat('non');

              }
               
            }*/
            if ($request->get('etat')) {
                $seance->setEtat('oui');
            }
            else{
                $seance->setEtat('non');

              }
        
          $seance->setEnseignant($seance->getEnseignant());
          $seance->setObjectif($seance->getObjectif());
          $seance->setObjectifSuivant($seance->getObjectifSuivant());
          $seance->setAutreInfo($seance->getAutreInfo());
          $seance->setObservation($seance->getObservation());
          $seance->setCodeSequence($seance->getCodeSequence());
          

             $entityManager->flush();

        return   $this->redirectToRoute('list_seance');

    
   /* 

    return   $this->render('ajouter_seance.html.twig', [
            'form' => $form->createView(),
            'error'=>'le delai invalaid'
        ]);*/


      
 }







             return   $this->render('update_seance.html.twig', [
            'form' => $form->createView(),
            'error'=>''
        ]);



}


/**
     * @Route("/absence/{id}", name="absence")
     */
    public function absence($id,Request $request): Response
   
    {
       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $entityManager = $this->getDoctrine()->getManager();
        $seance = $entityManager->getRepository(Seance::class)->find($id);
        $groupe = $entityManager->getRepository(Groupe::class)->find($seance->getGroupe());
        $affectation= $entityManager->getRepository(Affectation::class)->findOneByIdGroupe($groupe->getId());
         $adherents=[];
       foreach ( $affectation as  $value) {

          $adherents[]= $entityManager->getRepository(Utulisateur::class)->find($value->getIdAdherent());
     
      }
      if (isset($_POST['enregistrer'])) {
            foreach ($adherents as  $value) {
                if (isset($_POST[$value->getId()])) {
                  $absence= new Absence();
         $absence->setIdSeance($id);

         $absence->setIdAdherent($value->getId());
        $absence->setDate(\DateTime::createFromFormat('Y-m-d', $_POST['date_seance']));
        $absence->setEtat('oui');
         $entityManager->persist($absence);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();


                }else
                {
 $absence= new Absence();
         $absence->setIdSeance($id);

         $absence->setIdAdherent($value->getId());
        $absence->setDate(\DateTime::createFromFormat('Y-m-d', $_POST['date_seance']));
        $absence->setEtat('non');
         $entityManager->persist($absence);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
                }
        
     }
     return $this->redirectToRoute('list_seance');
      }

      return $this->render('absence.html.twig', [
        'adherents'=>$adherents
    ]);
    }

    /**
     * @Route("/emploi" , name="emploi" )
     */



public function emploi(SeanceRepository $var,Request $request):Response


    {    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
       

        $seance= $var->findAll();
            $entityManager = $this->getDoctrine()->getManager();

       $groupe = $entityManager->getRepository(Groupe::class)->findAll();
       

 if (isset($_POST['week'])) {

$week=date('W',strtotime($request->get('week')));
$annee=date('Y',strtotime($request->get('week')));
$lundi=[];$mardi=[];$mercredi=[];$jeudi=[];$vendredi=[];$samedi=[];$dimanche=[];
 $seances= array();
           foreach ($seance as  $value) {
            if ($week==date('W',strtotime($value->getdate()->format('Y-m-d'))) and $annee== date('Y',strtotime($value->getdate()->format('Y-m-d'))) ) {
             $seances[]=$value;
             if ($value->getJour()=='lundi') {
                 $lundi[]=$value;
             }
              if ($value->getJour()=='mardi') {
                 $mardi[]=$value;
             }

              if ($value->getJour()=='mercredi') {
                 $mercredi[]=$value;
             }
              if ($value->getJour()=='jeudi') {
                 $jeudi[]=$value;
             }
              if ($value->getJour()=='vendredi') {
                 $vendredi[]=$value;
             }
              if ($value->getJour()=='samedi') {
                 $samedi[]=$value;
             }
              if ($value->getJour()=='dimanche') {
                 $dimanche[]=$value;
             }
              

           //  return new Response(date('W',strtotime($value->getdate()->format('Y-m-d'))));
            }
        
           //return new Response($value->getDate()->format('w'));
       }
        
            return   $this->render('emploi1.html.twig',
                ['seances'=> $seances,
                'groupes'=>$groupe,
                'lundi'=>$lundi,
                'mardi'=>$mardi,
                'mercredi'=>$mercredi,
                'jeudi'=>$jeudi,
                'vendredi'=>$vendredi,
                'samedi'=>$samedi,
                'dimanche'=>$dimanche,
                "date"=>$_POST['week']
                ]);
        

    }
     return   $this->render('emploi1.html.twig',
                ['seances'=> '',
                'groupes'=>$groupe,
                'lundi'=>'',
                'mardi'=>'',
                'mercredi'=>'',
                'jeudi'=>'',
                'vendredi'=>'',
                'samedi'=>'',
                'dimanche'=>'',
                'date'=>''
                ]);
}



 






}


?>