<?php
namespace Tests\Feature;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;
class ProductPagesTest extends TestCase {
 public function test_product_cards_and_detail_pages_are_available():void{$product=Product::first();$this->assertNotNull($product);$this->get('/products')->assertOk()->assertSee('View Details');$this->get('/products/'.$product->slug)->assertOk()->assertSee($product->name)->assertSee('Related Products')->assertSee('Request Shipping Quote');}
 public function test_admin_routes_require_login():void{$this->get('/admin')->assertRedirect('/admin/login');$this->get('/admin/products')->assertRedirect('/admin/login');}
 public function test_admin_can_login_and_access_product_management():void{$product=Product::first();$user=User::where('email','admin@nexport.test')->firstOrFail();$this->actingAs($user)->get('/admin/products')->assertOk()->assertSee('Product Management');$this->actingAs($user)->get('/admin/products/'.$product->id.'/edit')->assertOk()->assertSee('Dynamic Specifications')->assertSee('Product Gallery');}
 public function test_admin_can_login_with_credentials():void{$this->post('/admin/login',['email'=>'admin@nexport.test','password'=>'password'])->assertRedirect('/admin');$this->assertAuthenticated();}
 public function test_language_can_be_switched_to_french():void{$this->from('/products')->get('/language/fr')->assertRedirect('/products');$this->get('/')->assertOk()->assertSee('Commerce mondial')->assertSee('Explorer les produits');$this->get('/products')->assertOk()->assertSee('Produits')->assertSee('Voir les détails')->assertSee('Toutes les catégories');$this->get('/about')->assertOk()->assertSee('Fondé sur la confiance')->assertSee('Qui sommes-nous');$this->get('/services')->assertOk()->assertSee('Approvisionnement international');$this->get('/tracking')->assertOk()->assertSee('Suivez votre expédition');$this->get('/contact')->assertOk()->assertSee('Envoyer le message');$this->assertSame('fr',session('locale'));}
 public function test_admin_interface_is_translated_to_french():void{$user=User::where('email','admin@nexport.test')->firstOrFail();$this->actingAs($user)->withSession(['locale'=>'fr'])->get('/admin')->assertOk()->assertSee('Vue d’ensemble des opérations')->assertSee('Gérer les produits');$this->actingAs($user)->withSession(['locale'=>'fr'])->get('/admin/products')->assertOk()->assertSee('Gestion des produits')->assertSee('Ajouter un produit');}
}
