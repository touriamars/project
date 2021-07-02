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

class CommandeController extends AbstractController
{

    /**
     * @Route("/ajouter_commande/{id}", name="ajouter_commande")
     */
    public function ajouter_commande(Request $request,$id){
       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

       $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->find($produit->getIdFournisseur());
        


        

             if (isset($_POST['ajouter']))

 {     $entityManager = $this->getDoctrine()->getManager();

$prix=$_POST['quantite']*($produit->getPrixHt()+$produit->getPrixHt()*$produit->gettva()/100)+$produit->getFraixStockage();
       $commande = new Commande();
    $commande->setQuantite($_POST['quantite']);
    $commande->setIdProduit($id);
    $commande->setDate(new \DateTime('now'));
    $commande->setPrixTotal($prix);



                  $entityManager->persist($commande);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
     

        return   $this->redirectToRoute('list_produit');
 }







            return   $this->render('ajouter_commande.html.twig',[
                'produit'=>$produit,
                "societe"=>$fournisseur->getSociete()]
                
           );


    }



/**
     * @Route("/list_commande" , name="list_commande" )
     */



public function list_commande():Response

{
   $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
     $entityManager = $this->getDoctrine()->getManager();
        $commande = $entityManager->getRepository(Commande::class)->findAll();
        $i=0;
        foreach ( $commande as  $value) {
                 $paiement= $entityManager->getRepository(PaiementCommande::class)->findBy([
    "id_commande" => $value->getid(),
   
]);
                 if (!$paiement) {
                     $commandes[$i]['paye']=false;
                   
                 }
                 else{
                    $commandes[$i]['paye']=true;
                 }
              $produit = $entityManager->getRepository(Produit::class)->find($value->getIdProduit());
              $fournisseur = $entityManager->getRepository(Fournisseur::class)->find($produit->getIdFournisseur());
              $commandes[$i]['lib']=$produit->getLib();
             $commandes[$i]['prix_ht']=$produit->getPrixHt();
            $commandes[$i]['tva']=$produit->getTva();
            $commandes[$i]['societe']=$fournisseur->getSociete();
             $commandes[$i]['quantite']=$value->getQuantite();
              $commandes[$i]['prix']=$value->getPrixTotal();
                $commandes[$i]['id']=$value->getid();
                 $commandes[$i]['id_p']=$produit->getid();




                $i++;
          
        }

      return $this->render('list_commande.html.twig' ,[
            'commandes' => $commandes
           ]);
        
    }



   /**
     * @Route("/modifier_commande/{id}/{id_commande}", name="modifier_commande")
     */
    public function modifier_commande(Request $request,$id,$id_commande){
       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

       $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->find($produit->getIdFournisseur());
         $commande = $entityManager->getRepository(Commande::class)->find($id_commande);
        


        

             if (isset($_POST['ajouter']))

 {     $entityManager = $this->getDoctrine()->getManager();

$prix=$_POST['quantite']*($produit->getPrixHt()+$produit->getPrixHt()*$produit->gettva()/100)+$produit->getFraixStockage();
      
    $commande->setQuantite($_POST['quantite']);
  
   
    $commande->setPrixTotal($prix);



                

        
        $entityManager->flush();
     

        return   $this->redirectToRoute('list_commande');
 }







            return   $this->render('modifier_commande.html.twig',[
                'produit'=>$produit,
                "societe"=>$fournisseur->getSociete(),
                'quantite'=>$commande->getQuantite()]
                
           );


    }




   
 /**
     * @Route("/delete_commande/{id}" , name="delete_commande" )
     */



public function delete_commande(Commande $commande ):Response


    {  
       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
       $entityManager=  $this->getDoctrine()->getManager();
       $entityManager->remove($commande);
       $entityManager->flush();




            return   $this->redirectToRoute('list_commande');
        
    } 

}



?>