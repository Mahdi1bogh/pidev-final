<?php

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/panier', name: 'index')]
public function index(SessionInterface $session, ProductRepository $ProductRepository)
{
    $panier = $session->get("panier", []);

    // On "fabrique" les données
    $dataPanier = [];
    $total = 0;

    foreach($panier as $id => $quantite){
        $product = $ProductRepository->find($id);
        $dataPanier[] = [
            "produit" => $product,
            "quantite" => $quantite
        ];
        $total += $product->getPrice() * $quantite;
    }

    return $this->render('home/panier.html.twig', compact("dataPanier", "total"));
} 
    #[Route('/wishlist', name: 'wishlist')]
    public function wishlist(SessionInterface $session, ProductRepository $ProductRepository)
    {
        // Get the wishlist array from the session storage
        $wishlist = $session->get('wishlist', []);

        // Get the list of products in the wishlist
        $produits = $ProductRepository->findBy(['id' => $wishlist]);

        return $this->render('cart/wishlist.html.twig', [
            'produits' => $produits,
        ]);
    }
    #[Route('/wishlist/add/{productId}', name: 'wishlist_add')]
    public function addToWishlist($productId, SessionInterface $session)
    {
        // Get the wishlist array from the session storage
        $wishlist = $session->get('wishlist', []);

        // Add the product ID to the wishlist array if it's not already in the array
        if (!in_array($productId, $wishlist)) {
            $wishlist[] = $productId;
            $session->set('wishlist', $wishlist);
        }

        // Redirect to the product detail page
        return $this->redirectToRoute('cart_wishlist', ['id' => $productId]);
    }
    #[Route('/wishlist/remove/{productId}', name: 'wishlist_remove')]
    public function removeFromWishlist($productId, SessionInterface $session)
{
    // Get the wishlist array from the session storage
    $wishlist = $session->get('wishlist', []);

    // Remove the product ID from the wishlist array if it exists
    if (($key = array_search($productId, $wishlist)) !== false) {
        unset($wishlist[$key]);
        $session->set('wishlist', $wishlist);
    }

    // Redirect to the wishlist page
    return $this->redirectToRoute('cart_wishlist');
}

    #[Route('/add/{id}', name: 'add')]
    public function add(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    #[Route('/delete', name: 'delete_all')]
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("cart_index");
    }
}
