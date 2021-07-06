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

class SecurityController extends AbstractController
{
private  $passwordEncoder;

             public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder =$passwordEncoder;
    }


    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
       

        // get the login error if there is one
        if ($this->getUser()) {
             return $this->redirectToRoute('home2');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
 }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {


        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');

         return $this->redirectToRoute('app_login');
    }

   /**
     * @Route("/acceuil" , name="acceuil" )
     */



public function acceuil( ):Response


    {  


            return   $this->render('acceuil.html.twig');
        
    }







    






     /**
     * @Route("/ajouter_user" , name="ajouter_user" )
     */



public function ajouter_user(Request $request):Response


    {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');   
       $entityManager = $this->getDoctrine()->getManager();
        $product = new User();
    

        $form = $this->createFormBuilder( $product)
            ->add('nom_prenom', TextType::class)
             ->add('email', EmailType::class)
              ->add('password',PasswordType::class)
               ->add('tele', TextType::class ,array(
    'attr' => array('maxlength' => 10,'minlength' => 10),
))
                ->add('ROLES',   ChoiceType::class, array(
            'choices' => array(
                'Administrateur' => 'ROLE_ADMIN',
                
                'Opérateur' => 'ROLE_USER'
            ),
            'expanded' => true,
            'mapped'=>false,
           
            "required"=>true
        ))

            ->add('envoyer', SubmitType::class, ['label' => 'Ajouter'])
            ->getForm();
             $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            $Prenom=array();
            $Prenom[]=$form->get('ROLES')->getData();

            

  

               $pass = $this->passwordEncoder->encodePassword($product, $product->getPassword());
              
           $product->setPassword($pass);
           $product->setEmail($product->getEmail());
           $product->setNomPrenom($product->getNomPrenom());
           $product->setTele($product->getTele());
           $product->setROles( $Prenom);
            $product->setEtat('active');



             $entityManager->persist($product);

             $entityManager->flush();
               


           
          

               return $this->redirectToRoute('list_user');
        }


               



             return $this->render('user.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/update_user/{id}" , name="update_user" )
     */

    public function update_user(Request $request,$id):Response


    {  
    $this->denyAccessUnlessGranted('ROLE_ADMIN'); 
       $entityManager = $this->getDoctrine()->getManager();
       $user= $entityManager->getRepository(User::class)->find($id);
        $product = new User();
    

        $form = $this->createFormBuilder( $product)
            ->add('nom_prenom', TextType::class,array(
                'data'=>$user->getNomPrenom()))
             ->add('email', EmailType::class,array(
                'data'=>$user->getEmail()))
              ->add('password',PasswordType::class,array(
                'data'=>$user->getPassword()))
               ->add('tele', TextType::class,array(
                'data'=>$user->getTele(),  'attr' => array('maxlength' => 10, 'minlength' => 10,)))
                ->add('ROLES',   ChoiceType::class, array(
            'choices' => array(
                'Administrateur' => 'ROLE_ADMIN',
                
                'Opérateur' => 'ROLE_USER'
            ),
            'expanded' => true,
            'mapped'=>false,
           
            "required"=>true
        ))

            ->add('envoyer', SubmitType::class, ['label' => 'Modifier'])
            ->getForm();
             $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            $Prenom=array();
             $Prenom[]=$form->get('ROLES')->getData();


               $pass = $this->passwordEncoder->encodePassword($product, $product->getPassword());
              
           $user->setPassword($pass);
           $user->setEmail($product->getEmail());
           $user->setNomPrenom($product->getNomPrenom());
           $user->setTele($product->getTele());
           $user->setROles( $Prenom);
            $user->setEtat('active');



           

             $entityManager->flush();
               


           
          

               return $this->redirectToRoute('list_user');
        }


               



             return $this->render('user.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/list_user" , name="list_user" )
     */



public function list_user(UserRepository $var):Response

{ $this->denyAccessUnlessGranted('ROLE_ADMIN');
       $users= $var->findAll();

      return $this->render('list_user.html.twig' ,[
            'users' => $users ]);
        
    }





    /**
     * @Route("/blouquer/{id}" , name="bloquer" )
     */



public function blouquer($id)
{
$this->denyAccessUnlessGranted('ROLE_ADMIN');
    $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);


         $user->setEtat('blouquer');
        $entityManager->flush();

        return $this->redirectToRoute('list_user'
        );

}



    /**
     * @Route("/deblouquer/{id}" , name="debloquer" )
     */



public function deblouquer($id)
{
$this->denyAccessUnlessGranted('ROLE_ADMIN');
    $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);


         $user->setEtat('active');
        $entityManager->flush();

        return $this->redirectToRoute('list_user'
        );

}
      

       /**
     * @Route("/delete_user/{id}" , name="delete_user" )
     */



public function delete_user(User $user ):Response


    {  $this->denyAccessUnlessGranted('ROLE_ADMIN');
       $entityManager=  $this->getDoctrine()->getManager();
       $entityManager->remove($user);
       $entityManager->flush();


      /*$Task= $var->find($id);

      return $this->render('home1.html.twig' ,[
            'form' => $Task->getImage() ]);
*/

            return   $this->redirectToRoute('list_user');
        
    }  





    /**
     * @Route("/oublier_mot_de_passe" , name="oublier_mot_de_passe"  )
     */



public function oublier_mot_de_passe(Request $request, \Swift_Mailer $mailer):Response

{  

$upload = new User();



         $form1 = $this->createFormBuilder($upload)
            ->setAction('oublier_mot_de_passe')
            ->setMethod('GET')
             ->add('email',EmailType::class)

            ->add('save', SubmitType::class, ['label' => 'Rechercher'])
            ->getForm();


             $form1->handleRequest($request);





 if ($form1->isSubmitted() && $form1->isValid())

 {    
              $entityManager = $this->getDoctrine()->getManager();

         $repository = $this->getDoctrine()->getRepository(User::class);
         $user= $repository->findOneBySomeFieEmail($form1->get('email')->getData());



           if($user)
           {

            $pass=random_bytes(10);

            $message = (new \Swift_Message('email de nouveau mot de passe'))
        ->setFrom('marstouriya7@gmail.com')
        ->setTo('marstouriya6@gmail.com')
        ->setBody(bin2hex($pass));
           $mailer->send($message);
          $password = $this->passwordEncoder->encodePassword($user, bin2hex($pass));

    $user->setPassword($password);
     
      $entityManager ->flush();
       

       
    return $this->redirectToRoute('modifier_mot_de_passe'); 
           }
       
 


     return $this->render('exemple1.html.twig', [
            'form' => $form1->createView(),
            'error' =>'email invalid'
        ]);

   
 }




   return $this->render('exemple1.html.twig', [
            'form' => $form1->createView(),
            'error' =>''
        ]);
        
    }




   /**
     * @Route("/paiement" , name="paiement" )
     */



public function paiement(UtulisateurRepository $var):Response


   {      if (isset($_POST['ok'])){
      $entityManager = $this->getDoctrine()->getManager();

        $paiement = new Paiement();
        $id=(int)$_POST['id1'];
        $montant=(int)$_POST['montant'];
        $date1=strtotime($_POST['date_debut']);
       $date = new \DateTime($_POST['date_debut']);
       $date_fin = new \DateTime($_POST['date_fin']);
        //$date_debut=date('yyyy-MM-dd',$date);

          $paiement->setIdAdherent($id);
           $paiement->setMontantPaye($montant);
           $paiement->setDateDebut($date);
           $paiement->setDateFin($date_fin);



             $entityManager->persist($paiement);

             $entityManager->flush();
              $entityManager = $this->getDoctrine()->getManager();

     
        $ad = $entityManager->getRepository(Utulisateur::class)->find($id);
           $image=$ad->getImage();

             return   $this->redirectToRoute('pdf',array(
        'id'=>$id,

        'date_debut'=>date_format($date, 'Y-m-d '),
        'date_fin'=> date_format($date_fin, 'Y-m-d '),
        'montant'=>$montant,
'image'=>$image
    ));
 return $this->redirectToRoute('list_seance');

    

    }



 $entityManager = $this->getDoctrine()->getManager();


     $adherent= $entityManager->getRepository(Affectation::class)->findAll();

            $i=0;
        foreach ($adherent as  $values) {
             
        $groupeadherent = $entityManager->getRepository(Groupe::class)->find($values->getIdGroupe());
         $value= $entityManager->getRepository(Utulisateur::class)->find($values->getIdAdherent());
         $adherent1[]=$value;
           $adherents[$i]['id']=$value->getId();
           $adherents[$i]['nomfrancais']=$value->getnomFrancais();
           $adherents[$i]['age']=$value->getAge();
           $adherents[$i]['niveau']=$value->getNiveau();
           $adherents[$i]['groupe']= $groupeadherent->getLib();
           $adherents[$i]['dateinscription']=date_format($value->getDateinscription(),"Y/m/d");
         $i++;

        }
        $adherents = json_encode($adherents);



            return   $this->render('paiement.html.twig',['adherents'=>$adherents,
                'adherent'=>$adherent1,
                




                ]);




        
    }



  
/**
     * @Route("/pdf/{id}/{date_debut}/{date_fin}/{montant}/{image}" , name="pdf" )
     */


 public function pdf($id,$date_debut,$date_fin,$montant,$image)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
     $pdfOptions->setIsRemoteEnabled(true);

        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('pdf.html.twig',[
        'id'=>$id,
        'image'=>$image,
        'date_debut'=>$date_debut,
        'date_fin'=> $date_fin,
         'montant'=>$montant]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
 return $this->redirectToRoute('list_seance');

       
    }

 

    /**
     * @Route("/modifier_mot_de_passe" , name="modifier_mot_de_passe"  )
     */



public function modifier_mot_de_passe(Request $request):Response

{  

 if (isset($_GET['modifier']))

 {    
              $entityManager = $this->getDoctrine()->getManager();

         $repository = $this->getDoctrine()->getRepository(User::class);
         $user= $repository->findOneBySomeFieEmail($_GET['email']);



           if($user)
           {

            
          $password = $this->passwordEncoder->isPasswordValid($user, $_GET['password']);

     if (!$this->passwordEncoder->isPasswordValid($user, $_GET['password'])) {
 
     return $this->render('modifier_mot_de_passe.html.twig', [
           
            'error' =>'le mot de passe invalid'
        ]);
         
     }
     else{
       
         $password1 = $this->passwordEncoder->encodePassword($user,$_GET['password1']);
        $user->setPassword($password1);
          $entityManager ->flush();
         return $this->redirectToRoute('app_login'); 
     }
     
    
       
 
        // you can remove the following code if you don't define a text version for your emails
      

       


    
           }
       
 


     return $this->render('modifier_mot_de_passe.html.twig', [
           
            'error' =>'email  invalid'
        ]);

   
 }




   return $this->render('modifier_mot_de_passe.html.twig', [
           
            'error' =>''
        ]);
        
    }


    

/**
     * @Route("/paiement_adherent/{id}", name="paiement_adherent")
     */
    public function paiement_adherent($id){
        $entityManager = $this->getDoctrine()->getManager();
        $Paiement = $entityManager->getRepository(Paiement::class)->findOneByIdAdherent($id);
        $affectation = $entityManager->getRepository(Affectation::class)->findOneByIdAdherent($id);
        if ($affectation) {
         $groupe=$entityManager->getRepository(Groupe::class)->find($affectation->getIdGroupe());
         $nom_groupe=$groupe->getLib();
        }
        else{
          $nom_groupe='';
        }

        $adherent = $entityManager->getRepository(Utulisateur::class)->find($id);



        return $this->render('paiement_adherent.html.twig',[
             'adherent'=>$adherent,
              'paiements'=>$Paiement,
              'groupe'=> $nom_groupe

        ]);


    }











      

    
  

}