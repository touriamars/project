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
class AdherentController extends AbstractController

{





    /**
     * @Route("/home2", name="home2")
     */
    public function list_adherent(UtulisateurRepository $var)
    {     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
       $adherent= $var->findAll();

      return $this->render('home2.html.twig' ,[
            'task' => $adherent ]);
    }
     /**
     * @Route("/home", name="home")
     */
    public function home(Request $request):Response

    {
       // $this->denyAccessUnlessGranted('ROLE_USER');
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
 $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
             $entityManager = $this->getDoctrine()->getManager();

        $upload = new Utulisateur();

             if (isset($_POST['ajouter'])) {

              // return new Response($request->files->get('image'));

                   
         $file=$request->files->get('image');
        //  var_dump($form->getData());die;
            //var_dump($form->getData()) ; 

               $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);

                $file1=$request->files->get('image_cni');

                $fileName1=md5(uniqid()).'.'.$file1->guessExtension();
                $file1->move($this->getParameter('upload_directory'), $fileName1);

                  



                $upload->setImage($fileName);
                $upload->setImageCni($fileName1);
                $upload->setNomFrancais($_POST['nom_francais']);
                $upload->setPrenomFrancais($_POST['prenom_francais']);
                $upload->setNomArab($_POST['nom_arab']);
                $upload->setPrenomArab($_POST['prenom_arab']);
                $upload->setTelephone($_POST['telephone']);
                $upload->setCni($_POST['cni']);
                $upload->setEmail($_POST['email']);
                $upload->setNiveau($_POST['niveau']);
                $upload->setNiveauDeplome($_POST['niveau_deplome']);
                $upload->setAge($_POST['age']);
                $upload->setSexe($_POST['sexe']);
                $upload->setMotPasse(($_POST['mot_de_passe']));
                $upload->setMobile($_POST['mobile']);
                $upload->setVille($_POST['ville']);
                $upload->setAdress($_POST['adress']);
                $upload->setVilleActuel($_POST['ville_actuel']);
                $upload->setlieuNaissanceFrancais($_POST['lieu_naissance_francais']);
                $upload->setLieuNaissanceArab($_POST['lieu_naissance_arab']);
                $upload->setDateNaissance(\DateTime::createFromFormat('Y-m-d', $_POST['date_naissance']));
                 $upload->setDateInscription(\DateTime::createFromFormat('Y-m-d', $_POST['date_inscription']));
                $upload->setFonctionTuteur($_POST['fonction_tuteur']);
                $upload->setTelephone($_POST['telephone']);

                $upload->setTeleTuteur($_POST['tele_tuteur']);
                $upload->setAutreMaladies($_POST['autre_maladies']);
                $upload->setAllergie($_POST['allergie']);
                



                $entityManager->persist($upload);
                   $entityManager->flush();

       return   $this->redirectToRoute('home2');
             }


              return $this->render('home.html.twig');

        
    }




   /**
     * @Route("/delete/{id}" , name="delete" )
     */



public function delete($id):Response


    {   $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $adherent = $entityManager->getRepository(Utulisateur::class)->find($id);
       
       $groupe1 = $entityManager->getRepository(GroupeAdherent::class)->findOneByIdAdherent($id);
           $groupe2 = $entityManager->getRepository(Affectation::class)->findOneByIdAdherent($id);
            $absences = $entityManager->getRepository(Absence::class)->findBy([
    "id_adherent" => $id,
   
]);
            foreach ($absences as $absence) {
              $entityManager->remove($absence);
       $entityManager->flush();
            }
if ($groupe1) {
 $entityManager->remove($groupe1);
       $entityManager->flush();
}

       if ($groupe2) {
              $groupe = $entityManager->getRepository(Groupe::class)->find($groupe2->getIdGroupe());
               $groupe->setNbAdherent($groupe->getNbAdherent()-1);
        $entityManager->flush();
 $entityManager->remove($groupe2);
       $entityManager->flush();
}
       $entityManager->remove($adherent);
       $entityManager->flush();

  

            return   $this->redirectToRoute('home2');
        
    }


    /**
     * @Route("/update/{id}" , name="update" )
     */







    public function update($id,Request $request ):Response


    {   $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

     $entityManager = $this->getDoctrine()->getManager();
        $upload = $entityManager->getRepository(Utulisateur::class)->find($id);



                 if (isset($_POST['modifier'])) {

              // return new Response($request->files->get('image'));
          if ($request->files->get('image')) {
              $file=$request->files->get('image');
             $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                $upload->setImage($fileName);
               
          }
       
 if ($request->files->get('image_cni')) {
                $file1=$request->files->get('image_cni');

                $fileName1=md5(uniqid()).'.'.$file1->guessExtension();
                $file1->move($this->getParameter('upload_directory'), $fileName1);

                  
 $upload->setImageCni($fileName1);
}

                


                $upload->setNomFrancais($_POST['nom_francais']);
                $upload->setPrenomFrancais($_POST['prenom_francais']);
                $upload->setNomArab($_POST['nom_arab']);
                $upload->setPrenomArab($_POST['prenom_arab']);
                $upload->setTelephone($_POST['telephone']);
                $upload->setCni($_POST['cni']);
                $upload->setEmail($_POST['email']);
                $upload->setNiveau($_POST['niveau']);
                $upload->setNiveauDeplome($_POST['niveau_deplome']);
                $upload->setAge($_POST['age']);
                $upload->setSexe($_POST['sexe']);
                $upload->setMotPasse(($_POST['mot_de_passe']));
                $upload->setMobile($_POST['mobile']);
                $upload->setVille($_POST['ville']);
                $upload->setAdress($_POST['adress']);
                $upload->setVilleActuel($_POST['ville_actuel']);
                $upload->setlieuNaissanceFrancais($_POST['lieu_naissance_francais']);
                $upload->setLieuNaissanceArab($_POST['lieu_naissance_arab']);
                $upload->setDateNaissance(\DateTime::createFromFormat('Y-m-d', $_POST['date_naissance']));
                 $upload->setDateInscription(\DateTime::createFromFormat('Y-m-d', $_POST['date_inscription']));
                $upload->setFonctionTuteur($_POST['fonction_tuteur']);
                $upload->setTelephone($_POST['telephone']);

                $upload->setTeleTuteur($_POST['tele_tuteur']);
                $upload->setAutreMaladies($_POST['autre_maladies']);
                $upload->setAllergie($_POST['allergie']);
                



                $entityManager->persist($upload);
                   $entityManager->flush();
                  return $this->redirectToRoute('home2');
        

                }

             return $this->render('update_adherent.html.twig', [
            'user' => $upload,
        ]);
        
    }

 /**
     * @Route("/profile/{id}" , name="profile" )
     */



public function profile( $id):Response


    {       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');    

      $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Utulisateur::class)->find($id);
        return $this->render('profile.html.twig',[
            'user' => $user
        ]);
        
    }



    /**
     * @Route("/paiement1" , name="paiement1" )
     */



public function paiement1():Response


    { 

       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  if (isset($_POST['paye'])) {

          
            if ($_POST['id1']=='inscription') {
                 $entityManager = $this->getDoctrine()->getManager();

        $paiement = new Paiement();
        $id=(int)$_POST['id'];
        $montant=(int)$_POST['montant'];
        $date1=strtotime($_POST['date_debut']);
       $date = new \DateTime($_POST['date_debut']);
       $date_fin = new \DateTime($_POST['date_fin']);
        //$date_debut=date('yyyy-MM-dd',$date);

          $paiement->setIdAdherent($id);
           $paiement->setMontantPaye($montant);
           $paiement->setDateDebut($date);
           $paiement->setDateFin($date_fin);
           $paiement->setTypeRevenu('inscription');



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


             
            }else{ //return new response($_POST['id_type']);

                         $entityManager = $this->getDoctrine()->getManager();

        $paiement = new Paiement();
        $id=(int)$_POST['id'];
        $montant=(int)$_POST['montant'];
    
       $date = new \DateTime();
       $date_fin = new \DateTime();
         $type = $entityManager->getRepository(TypeRevenu::class)->findBy([
    "lib" => $_POST['id_type'],
   
]);
         foreach ($type as  $value) {
             $lib=$value->getLib();
         }

          $paiement->setIdAdherent($id);
           $paiement->setMontantPaye($montant);
           $paiement->setDateDebut($date);
           $paiement->setDateFin($date_fin);
           $paiement->setTypeRevenu($lib);



             $entityManager->persist($paiement);

             $entityManager->flush();

            }
           
        }




if (isset($_POST['ok'])) {

    $entityManager = $this->getDoctrine()->getManager();
        $adherent = $entityManager->getRepository(Utulisateur::class)->find($_POST['id']);
         $charges = $entityManager->getRepository(TypeRevenu::class)->findAll();
   return $this->render('paiement2.html.twig',[
    'adherent'=>$adherent,
'charges'=>$charges]);
}

        $entityManager = $this->getDoctrine()->getManager();
        $adherent = $entityManager->getRepository(Utulisateur::class)->findAll();
           if (isset($_POST['recherche'])) {
            if (!is_null($_POST['prenom']) and is_null($_POST['nom']) ) {

              $entityManager = $this->getDoctrine()->getManager();
        $adherent = $entityManager->getRepository(Utulisateur::class)->findBy([
    "nom_francais" => $_POST['nom'],
   
]);
          return $this->render('paiement1.html.twig',[
            'adherent'=>$adherent]);
            }
              
                     if (!is_null($_POST['nom']) and is_null($_POST['prenom']) ) {

              $entityManager = $this->getDoctrine()->getManager();
        $adherent = $entityManager->getRepository(Utulisateur::class)->findBy([
    "prenom_francais" => $_POST['prenom'],
   
]);
          return $this->render('paiement1.html.twig',[
            'adherent'=>$adherent]);
            }
             if (!is_null($_POST['nom']) and !is_null($_POST['prenom']) ) {

              $entityManager = $this->getDoctrine()->getManager();
        $adherent = $entityManager->getRepository(Utulisateur::class)->findBy([
    "prenom_francais" => $_POST['prenom'],
    "nom_francais" => $_POST['nom'],
   
   
]);
          return $this->render('paiement1.html.twig',[
            'adherent'=>$adherent]);
            }
    
    
      
    }  


 return $this->render('paiement1.html.twig',[
            'adherent'=>$adherent]);



}

   
}
?>