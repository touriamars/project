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

class FournisseurController extends AbstractController
{
    /**
     * @Route("/ajouter_fournisseur", name="ajouter_fournisseur")
     */
    public function ajouter_fournisseur(Request $request){

       


        

              if (isset($_POST['ajouter']))

 {     $entityManager = $this->getDoctrine()->getManager();


        $fournisseur = new Fournisseur();
         $fournisseur->setNom($_POST['nom']);
         $fournisseur->setPrenom($_POST['prenom']);
        $fournisseur->setSociete($_POST['societe']);
        $fournisseur->setAdresse($_POST['adress']);
        $fournisseur->setVille($_POST['ville']);
        $fournisseur->setCodePostal($_POST['code_postal']);
        $fournisseur->setFax($_POST['fax']);
         $fournisseur->setMobile($_POST['mobile']);
        $fournisseur->setTelephone($_POST['telephone']);
        $fournisseur->setEmail($_POST['email']);
        $fournisseur->setPays($_POST['pays']);



 $file=$request->files->get('image');
        //  var_dump($form->getData());die;
            //var_dump($form->getData()) ; 

               $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                 $fournisseur->setImage($fileName);
                  $entityManager->persist($fournisseur);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
      

        return   $this->redirectToRoute('list_fournisseur');
 }







            return   $this->render('ajouter_fournisseur.html.twig'
           );


    }



 /**
     * @Route("/list_fournisseur" , name="list_fournisseur" )
     */



public function list_fournisseur(FournisseurRepository $var):Response

{
       $fournisseurs= $var->findAll();

      return $this->render('list_fournisseur.html.twig' ,[
            'fournisseurs' => $fournisseurs ]);
        
    }



     /**
     * @Route("/modifier_fournisseur/{id}", name="modifier_fournisseur")
     */
    public function modifier_fournisseur(Request $request,$id){

       
           $entityManager = $this->getDoctrine()->getManager();
       $fournisseur = $entityManager->getRepository(Fournisseur::class)->find($id);

        

              if (isset($_POST['ajouter']))

 {     $entityManager = $this->getDoctrine()->getManager();


       
         $fournisseur->setNom($_POST['nom']);
         $fournisseur->setPrenom($_POST['prenom']);
        $fournisseur->setSociete($_POST['societe']);
        $fournisseur->setAdresse($_POST['adress']);
        $fournisseur->setVille($_POST['ville']);
        $fournisseur->setCodePostal($_POST['code_postal']);
        $fournisseur->setFax($_POST['fax']);
         $fournisseur->setMobile($_POST['mobile']);
        $fournisseur->setTelephone($_POST['telephone']);
        $fournisseur->setEmail($_POST['email']);
        $fournisseur->setPays($_POST['pays']);


if ($request->files->get('image')) {
  $file=$request->files->get('image');
        //  var_dump($form->getData());die;
            //var_dump($form->getData()) ; 

               $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                  $fournisseur->setImage($fileName);
}

                 

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
      

        return   $this->redirectToRoute('list_fournisseur');
 }







            return   $this->render('modifier_fournisseur.html.twig',['fournisseur'=>$fournisseur]
           );


    }
/**
     * @Route("/delete_fournisseur/{id}", name="delete_fournisseur")
     */

    public function delete_fournisseur(Fournisseur $fournisseur ):Response


    {  $this->denyAccessUnlessGranted('ROLE_ADMIN');
       $entityManager=  $this->getDoctrine()->getManager();
                  $produits = $entityManager->getRepository(Produit::class)->findBy([
    "id_fournisseur" => $id,
   
]);
foreach ($produits as $produit) {
            $commandes = $entityManager->getRepository(Commande::class)->findBy([
    "id_produit" => $produit->getId(),
   
]);
            foreach ( $commandes as  $commande) {
              $entityManager->remove( $commande);
       $entityManager->flush();
            }
              $entityManager->remove( $produit);
       $entityManager->flush();

 
}


       
       $entityManager->remove($fournisseur);
       $entityManager->flush();


     

            return   $this->redirectToRoute('list_fournisseur');
        
}



 /**
     * @Route("/recherche/{id}" , name="recherche" )
     */



public function recherche($id,Request $request):Response {
if (isset($_POST['ok'])) {
$commande=[];

       $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Produit::class)->findOneByIdFournisseur($id);
        $i=0;
        foreach ($produit as $value) {
             $commandes= $entityManager->getRepository(Commande::class)->findOneByIdProduit($value->getId());
             
             foreach ($commandes as  $value1) {  

   

                if ( $request->get('date_debut') <= $value1->getDate()->format('Y-m-d') and  $request->get('date_fin') >= $value1->getDate()->format('Y-m-d')  ) {
                    $commandes1[]=$value1;
                $commande[$i]['lib']=$value->getLib();
                $commande[$i]['quantite']=$value1->getQuantite();
                $commande[$i]['prix_ht']=$value->getPrixHt();
                $commande[$i]['prix_ttc']=$value->getPrixTtc();
                 $commande[$i]['tva']=$value->getTva();
                $commande[$i]['total']=$value1->getPrixTotal();


             
                     $i++;
             
                }
         
             }
      
        }
        return $this->render('recherche.html.twig',['commandes'=>$commande,
            'date_debut'=>$_POST['date_debut'],
            'date_fin'=>$_POST['date_fin']]);
     
       } 
       if (isset($_POST['valider'])) {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Produit::class)->findOneByIdFournisseur($id);
        foreach ($produit as $value) {
             $commandes= $entityManager->getRepository(Commande::class)->findOneByIdProduit($value->getId());
foreach (  $commandes as  $value) {
          $paiement= new PaiementCommande();
          $paiement->setIdCommande($value->getId());
           $paiement->setMode($_POST['mode']);
           $paiement->setDatePaiement(new \DateTime('now'));
 



                  $entityManager->persist($paiement);

        
        $entityManager->flush();
        }

         }


        
    

       }
   
     return $this->render('recherche.html.twig',['commandes'=>'',
            'date_debut'=>'',
            'date_fin'=>'']);

}


/**
     * @Route("/pdf2/{{societe}}/{commandes}/{date_paiement}" , name="pdf2" )
     */


 public function pdf2($societe,$commandes,$date_paiement)
    {
        /*
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
     $pdfOptions->setIsRemoteEnabled(true);

        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('pdf2.html.twig',[
        'societe'=>$societe,
        'cammandes'=>$cammandes,
        'date_paiement'=>$date_debut,
        ]);
        
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
 
*/
       
    }


}



?>