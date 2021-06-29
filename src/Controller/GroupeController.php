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

class GroupeController extends AbstractController
{


	/**
     * @Route("/ajouter_groupe" , name="ajouter_groupe" )
     */



public function ajouter_groupe(Request $request):Response


    {  
 $entityManager = $this->getDoctrine()->getManager();


        $groupe = new Groupe();



         $form = $this->createFormBuilder($groupe)
             ->setAction($this->generateUrl('ajouter_groupe'))
              ->add('lib',TextType::class, [
               
                
                'label' => false 
            ]  )

             ->add('nb_max_adherent',IntegerType::class, [
               
                
                'label' => false 
            ]  )  ->add('colors',ColorType::class, [
               
                
                'label' => false ,
                'mapped'=>false,
            ]  )
            
             ->add('tranche_age',ChoiceType::class, [
                'choices' => [
                    'enfant' => 'enfant',
                    'adulte'  => 'adulte',
                ],
      
                
                'label' => false 
            ]  )
                   ->add('sexe',ChoiceType::class, [
                'choices' => [
                    'Femme' => 'FEMME',
                    'Homme'  => 'HOMME',
                ],
       
                
                'label' => 'sexe' 
            ]  )

            ->add('save', SubmitType::class, ['label' => 'Ajouter un Groupe'])
            ->getForm();


             $form->handleRequest($request);





 if ($form->isSubmitted() && $form->isValid())

 {    $entityManager = $this->getDoctrine()->getManager();
        $groupe1 = $entityManager->getRepository(Groupe::class)->findOneByLib( $groupe->getLib());

if ($groupe1) {
    return   $this->render('groupe.html.twig', [
            'form' => $form->createView(),
            'error'=>'le groupe deja existe'
        ]);
}
   
        

        $groupe->setNbAdherent(0);
        $groupe->setNbMaxAdherent( $groupe->getNbMaxAdherent());
        $groupe->setSexe( $groupe->getSexe());
        $groupe->setTrancheAge( $groupe->getTrancheAge());  
         $groupe->setLib( $groupe->getLib()); 
           $groupe->setColor(strval($form['colors']->getData()));       



                $entityManager->persist($groupe);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

       return   $this->redirectToRoute('list_groupe');

 }







            return   $this->render('groupe.html.twig', [
            'form' => $form->createView(),
            'error'=>''
        ]);
        
    }  


    /**
     * @Route("/list_groupe" , name="list_groupe" )
     */

    public function list_groupe(GroupeRepository $var):Response

{
       $groupes= $var->findAll();

      return $this->render('list_groupe.html.twig' ,[
            'groupes' => $groupes ]);
        
    }


/**
     * @Route("/update_groupe/{id}" , name="update_groupe" )
     */







    public function update_groupe($id,Request $request)


    {   $this->denyAccessUnlessGranted('ROLE_ADMIN');

     $entityManager = $this->getDoctrine()->getManager();
        $groupe = $entityManager->getRepository(Groupe::class)->find($id);




       

 $form = $this->createFormBuilder($groupe)
            ->add('lib',TextType::class, [
               
                
                'label' => 'Libellé du groupe',
                'data' => $groupe->getLib() 
            ]  )
            

             ->add('nb_max_adherent',IntegerType::class, [
               
                
                'label' => false ,
                'data' => $groupe->getNbMaxAdherent() 
            ]  )
               ->add('colors',ColorType::class, [
               
                
                'label' => false ,
                'mapped'=>false,
                'data' => $groupe->getColor() 
            ]  )
            
             ->add('tranche_age',ChoiceType::class, [
                'choices' => [
                    'enfant' => 'enfant',
                    'adulte'  => 'adulte',
                ],
      
                
                'label' => false ,
                'data' => $groupe->getTrancheAge() 
            ]  )
                   ->add('sexe',ChoiceType::class, [
                'choices' => [
                    'Femme' => 'femme',
                    'Homme'  => 'homme',
                ],
       
                
                'label' => 'sexe' ,
                'data' => $groupe->getSexe() 
            ]  )

            ->add('save', SubmitType::class, ['label' => 'Modifier'])
            ->getForm();


             $form->handleRequest($request);





 if ($form->isSubmitted() && $form->isValid())

 {

    $entityManager = $this->getDoctrine()->getManager();
        $groupe1 = $entityManager->getRepository(Groupe::class)->findOneByLib( $groupe->getLib());

if ($groupe1 && $groupe1->getId()!=$groupe->getId()) {
    return   $this->render('update_groupe.html.twig', [
            'form' => $form->createView(),
            'groupe'=> $groupe,
            'error'=>'le groupe deja existe'
        ]);
}
if ($groupe->getNbAdherent()>$groupe->getNbMaxAdherent()) {
    return   $this->render('update_groupe.html.twig', [
            'form' => $form->createView(),
            'groupe'=> $groupe,
            'error'=>'le nombre d\'adherents superieur a le nombre max d\'adherents '
        ]);
}
   
       // $groupe->setNbAdherent( $groupe->getNbMaxAdherent());
        $groupe->setNbMaxAdherent( $groupe->getNbMaxAdherent());
        $groupe->setSexe( $groupe->getSexe());
        $groupe->setTrancheAge( $groupe->getTrancheAge()); 
         $groupe->setColor(strval($form['colors']->getData()));     
          $groupe->setLib( $groupe->getLib());   



             

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

       return   $this->redirectToRoute('list_groupe');

 }







            return   $this->render('update_groupe.html.twig', [
            'form' => $form->createView(),
            'groupe'=> $groupe,
            'error'=>''
        ]);
        



}

/**
     * @Route("/delete_groupe/{id}", name="delete_groupe")
     */
    public function delete_groupe(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $groupe = $entityManager->getRepository(Groupe::class)->find($id);
          $groupe1 = $entityManager->getRepository(GroupeAdherent::class)->findOneByIdGroupe($id);
           $groupe2 = $entityManager->getRepository(Affectation::class)->findOneByIdGroupe($id);
             $seance = $entityManager->getRepository(Seance::class)->findOneByIdGroupe($id);
           $entityManager->remove($groupe);
$entityManager->flush();
foreach ($groupe1 as  $value) {
    $entityManager->remove($value);
    $entityManager->flush();
}foreach ($seance as  $value) {
    $entityManager->remove($value);
    $entityManager->flush();
}
      

  
          foreach ($groupe2 as  $value) {
    $entityManager->remove($value);
    $entityManager->flush();
}

        
        return $this->redirectToRoute('list_groupe');
    }


     /**
     * @Route("/list_adherent_groupe/{id}" , name="list_adherent_groupe" )
     */



public function list_adherent_groupe($id,GroupeRepository $var):Response


    {   $groupe= $var->find($id);
          $entityManager = $this->getDoctrine()->getManager();
       $affectation = $entityManager->getRepository(Affectation::class)->findOneByIdGroupe( $groupe->getId());
 
      foreach ( $affectation as  $value) {

          $adherents[]= $entityManager->getRepository(Utulisateur::class)->find($value->getIdAdherent());
     
      }
       



           return   $this->render('list_adherent_dans_groupe.html.twig',
                ['adherents'=>$adherents,
                'id'=>$id,
                'lib'=>$groupe->getLib()
                ]);
        
    }



    /**
     * @Route("/changer_groupe/{id}/{id_groupe}" , name="changer_groupe" )
     */



public function changer_groupe($id,$id_groupe,GroupeRepository $var):Response


    {   $groupe= $var->findAll();
        
        if (isset($_POST['ok'])) {

       $entityManager = $this->getDoctrine()->getManager();
               $groupe2 = $entityManager->getRepository(Groupe::class)->find($id_groupe);
        $groupe1 = $entityManager->getRepository(Groupe::class)->find($_POST['groupe']);
            $affectation =  $entityManager->getRepository(Affectation::class)->findOneByIdAdherent($id);
        $groupeadherent = $entityManager->getRepository(GroupeAdherent::class)->findOneByIdAdherent($id);
            
   if ($affectation) {
          $affectation->setIdAdherent($id);
        $affectation->setIdGroupe($_POST['groupe']);
       $entityManager->flush();
       $groupeadherent->setIdGroupe($_POST['groupe']);
     
              $entityManager->flush();   
      }
      else{
        $affectation=new Affectation();
        $affectation->setIdAdherent($id);
        $affectation->setIdGroupe($_POST['groupe']);
         $entityManager->persist($affectation);
       $entityManager->flush();
       $groupeadherent= new GroupeAdherent();
       $groupeadherent->setIdAdherent($id);
       $groupeadherent->setIdGroupe($_POST['groupe']);
        $entityManager->persist($groupeadherent);
     
              $entityManager->flush();   


      }

       



               
                
 $groupe2->setNbAdherent( $groupe2->getNbAdherent()-1);
                       $entityManager->flush();
                 
             
                  $groupe1->setNbAdherent( $groupe1->getNbAdherent()+1);
                       $entityManager->flush();
                        
                       return  $this->redirectToRoute('list_groupe');

                     //  return new Response($id_groupe);
        }



            return   $this->render('changer_groupe.html.twig',
                ['groupes'=>$groupe,
                'id'=>$id,
                'id_groupe'=>$id_groupe

                ]);
        
    }



    /**
     * @Route("/affectation/{id}" , name="affectation" )
     */



public function affectation(UtulisateurRepository $var,$id):Response

{

    
     $entityManager = $this->getDoctrine()->getManager();
        $groupe1 = $entityManager->getRepository(Groupe::class)->find($id);

 $groupe= $var->findAll();
 $groupe11 = $entityManager->getRepository(Utulisateur::class)->affectation();

 // $groupe1 = $entityManager->getRepository(Groupe::class)->find($id);
        $limit=$groupe1->getNbMaxAdherent()-$groupe1->getNbAdherent();

    if (isset($_POST['ok'])) {
        $i=0;

        foreach ( $_POST['utulisateur']  as $variable) {
$affectation = new Affectation();
$groupeadherent = new GroupeAdherent();
            $i++;
          
        $affectation->setIdAdherent($variable);
        $affectation->setIdGroupe($id);
        $groupeadherent->setIdAdherent($variable);
       $groupeadherent->setIdGroupe($id);
     
             



                $entityManager->persist($affectation);
                 $entityManager->flush();

                  $entityManager->persist($groupeadherent);
                 $entityManager->flush();
        // actually executes the queries (i.e. the INSERT query)
            
        }


         $groupe1->setNbAdherent( $groupe1->getNbAdherent()+$i);


              

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

       
          return $this->redirectToRoute('list_groupe');
}

 return   $this->render('affectation.html.twig',
    [
            'groupe' => $groupe11,
             'id'=>$id,
             'limit'=>$limit,
             'lib'=>$groupe1->getLib()
        ]
       );
}




/**
     * @Route("/list_absence/{id}", name="list_absence")
     */
    public function list_absence($id)
    { $seances=[];
         $adherents=[];
          $entityManager = $this->getDoctrine()->getManager();
         $seances = $entityManager->getRepository(Seance::class)->findOneByIdGroupe($id);
        $groupe = $entityManager->getRepository(Groupe::class)->find($id);

         if (isset($_POST['id'])){

              $entityManager = $this->getDoctrine()->getManager();
         $absences = $entityManager->getRepository(Absence::class)->findByIdSeance($_POST['id']);
         $seance = $entityManager->getRepository(Seance::class)->find($_POST['id']);
         if ($absences) {
            foreach ($absences as $value) {

 $adherents[] = $entityManager->getRepository(Utulisateur::class)->find($value->getIdAdherent());
                
            }
         }
           return $this->render('list_absence.html.twig',[
        'seances'=> $seances,
        'adherents'=> $adherents,
        'absences'=>$absences,
        'groupe'=>$groupe->getLib(),
        'id'=>$_POST['id']
    ]);

         }

    return $this->render('list_absence.html.twig',[
        'seances'=> $seances,
         'adherents'=> '',
          'absences'=>'',
             'groupe'=>'',
             'id'=>'' ]);

    }



    
}






?>