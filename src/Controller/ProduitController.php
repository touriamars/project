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

class ProduitController extends AbstractController
{
 /**
     * @Route("/ajouter_produit", name="ajouter_produit")
     */
    public function ajouter_produit(Request $request){
       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

          $entityManager = $this->getDoctrine()->getManager();
        $fournisseurs = $entityManager->getRepository(Fournisseur::class)->findAll();


        

              if (isset($_POST['ajouter']))

 {     $entityManager = $this->getDoctrine()->getManager();


       $produit = new Produit();
        $produit->setReference($_POST['reference']);
        $produit->setLib($_POST['lib']);
       $produit->setdescription($_POST['description']);
        $produit->setPrixHt($_POST['prix_ht']);
        $ttc=$_POST['prix_ht']*$_POST['tva']/100;
        $produit->setPrixTtc($ttc);
      $produit->setTva($_POST['tva']);
       $produit->setStockMax($_POST['stock_max']);
       $produit->setStockAlerte($_POST['stock_alerte']);
       $produit->setFraixStockage($_POST['fraix_stockage']);
       $produit->setPoid($_POST['poid']);
    
        $produit->setEtat($_POST['etat']);
         $produit->setIdFournisseur($_POST['id']);



 $file=$request->files->get('image');
        //  var_dump($form->getData());die;
            //var_dump($form->getData()) ; 

               $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                 $produit->setImage($fileName);
                  $entityManager->persist($produit);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
     

        return   $this->redirectToRoute('list_produit');
 }







            return   $this->render('ajouter_produit.html.twig',[
                'fournisseurs'=>$fournisseurs]
           );


    }




    /**
     * @Route("/modifier_produit/{id}", name="modifier_produit")
     */
    public function modifier_produit(Request $request,$id){
       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

       
           $entityManager = $this->getDoctrine()->getManager();
       $produit = $entityManager->getRepository(Produit::class)->find($id);

        

              if (isset($_POST['modifier']))

 {    $entityManager = $this->getDoctrine()->getManager();


        $produit->setReference($_POST['reference']);
        $produit->setLib($_POST['lib']);
       $produit->setdescription($_POST['description']);
        $produit->setPrixHt($_POST['prix_ht']);
        $ttc=$_POST['prix_ht']*$_POST['tva']/100;
        $produit->setPrixTtc($ttc);
      $produit->setTva($_POST['tva']);
       $produit->setStockMax($_POST['stock_max']);
       $produit->setStockAlerte($_POST['stock_alerte']);
       $produit->setFraixStockage($_POST['fraix_stockage']);
       $produit->setPoid($_POST['poid']);
    
        $produit->setEtat($_POST['etat']);
         $produit->setIdFournisseur($_POST['id']);


if ($request->files->get('image')) {
  $file=$request->files->get('image');
        //  var_dump($form->getData());die;
            //var_dump($form->getData()) ; 

               $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                 $produit->setImage($fileName);
}

                 

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
      

        return   $this->redirectToRoute('list_produit');
 }



 $entityManager = $this->getDoctrine()->getManager();
        $fournisseurs = $entityManager->getRepository(Fournisseur::class)->findAll();



            return   $this->render('modifier_produit.html.twig',['produit'=>$produit, 'fournisseurs'=>$fournisseurs]
           );


    }
/**
     * @Route("/list_produit" , name="list_produit" )
     */



public function list_produit(FournisseurRepository $var,ProduitRepository $var1):Response

{    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
       $fournisseurs= $var->findAll();
        $produits= $var1->findAll();

      return $this->render('list_produit.html.twig' ,[
            'fournisseurs' => $fournisseurs,
            'produits'=>$produits ]);
        
    }

/**
     * @Route("/delete_produit/{id}", name="delete_produit")
     */

    public function delete_produit( $id):Response


    {   $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    $entityManager = $this->getDoctrine()->getManager();
      $produit = $entityManager->getRepository(Produit::class)->find($id);


              $commandes = $entityManager->getRepository(Commande::class)->findBy([
    "id_produit" => $id,
   
]);
            foreach ( $commandes as  $commande) {
              $entityManager->remove( $commande);
       $entityManager->flush();
            }
       $entityManager=  $this->getDoctrine()->getManager();
       $entityManager->remove($produit);
       $entityManager->flush();



            return   $this->redirectToRoute('list_produit');
        
}




}




?>