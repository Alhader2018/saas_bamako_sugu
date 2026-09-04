<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les utilisateurs avec rôles distincts (RBAC)
        $this->call(SuperAdminSeeder::class);

        User::updateOrCreate(
            ['email' => 'commandes@bamakosugu.com'],
            [
                'name' => 'Gestionnaire Commandes',
                'role' => 'order_manager',
                'phone' => '+223 76 11 22 33',
                'password' => bcrypt('orders1234'),
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'client@bamakosugu.com'],
            [
                'name' => 'Client Test',
                'role' => 'customer',
                'phone' => '+223 66 55 44 33',
                'password' => bcrypt('client1234'),
                'is_active' => true,
            ]
        );

        // Catégories conformes au prompt maître
        $categoriesData = [
            [
                'name' => 'Supermarché',
                'slug' => 'supermarche',
                'icon' => 'shopping-cart',
                'badge' => 'Essentiel',
                'description' => 'Épicerie, boissons, conserves, thés et riz de qualité.',
                'image_url' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80',
                'is_featured' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Mode',
                'slug' => 'mode',
                'icon' => 'shirt',
                'badge' => 'Tendance',
                'description' => 'Bazin riche malien, prêt-à-porter, maroquinerie et chaussures.',
                'image_url' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=600&q=80',
                'is_featured' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'Beauté',
                'slug' => 'beaute',
                'icon' => 'sparkles',
                'badge' => 'Soin Bio',
                'description' => 'Beurre de karité pur du Mali, parfums, soins et cosmétiques.',
                'image_url' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=600&q=80',
                'is_featured' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'High-Tech',
                'slug' => 'high-tech',
                'icon' => 'smartphone',
                'badge' => 'Garantie 1 an',
                'description' => 'Smartphones, accessoires, écouteurs, batteries de secours et TV.',
                'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=600&q=80',
                'is_featured' => true,
                'display_order' => 4,
            ],
            [
                'name' => 'Maison',
                'slug' => 'maison',
                'icon' => 'home',
                'badge' => 'Confort',
                'description' => 'Ventilateurs rechargeables, climatisation, mixeurs et décoration.',
                'image_url' => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=600&q=80',
                'is_featured' => true,
                'display_order' => 5,
            ],
            [
                'name' => 'Bébé',
                'slug' => 'bebe',
                'icon' => 'baby',
                'badge' => 'Douceur',
                'description' => 'Couches, laits de croissance, biberons et vêtements bébés.',
                'image_url' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?auto=format&fit=crop&w=600&q=80',
                'is_featured' => true,
                'display_order' => 6,
            ],
            [
                'name' => 'Fruits & Légumes',
                'slug' => 'fruits-legumes',
                'icon' => 'apple',
                'badge' => 'Frais du Jour',
                'description' => 'Mangues locales, tomates de Baguineda, oignons et agrumes.',
                'image_url' => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=600&q=80',
                'is_featured' => true,
                'display_order' => 7,
            ],
            [
                'name' => 'Auto',
                'slug' => 'auto',
                'icon' => 'car',
                'badge' => 'Entretien',
                'description' => 'Accessoires auto & moto, batteries, huiles moteur et sécurité.',
                'image_url' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?auto=format&fit=crop&w=600&q=80',
                'is_featured' => true,
                'display_order' => 8,
            ],
        ];

        $createdCategories = [];
        foreach ($categoriesData as $c) {
            $createdCategories[$c['slug']] = Category::create($c);
        }

        // Produits riches et calibrés pour le marché de Bamako
        $productsData = [
            // Supermarché
            [
                'category_slug' => 'supermarche',
                'name' => 'Sac de Riz Parfumé Dinor 25kg',
                'vendor_name' => 'Grand Moulin du Mali',
                'reference' => 'BKO-SP-001',
                'price' => 19500,
                'original_price' => 22000,
                'discount_percent' => 11,
                'badge' => 'Meilleure Vente',
                'stock' => 45,
                'rating' => 4.9,
                'reviews_count' => 64,
                'image_url' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1536304993881-ff6e9eefa2a6?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Riz long grain parfumé de qualité supérieure. Idéal pour le Tchep, riz au gras ou accompagnement quotidien des familles bamakoises. Cuisson uniforme et parfum délicat garanti.',
                'features' => [
                    'Conditionnement' => 'Sac renforcé 25 kg',
                    'Origine' => 'Import sélection certifiée',
                    'Grains' => 'Long grain parfumé 100% brisure zéro',
                ],
                'is_flash_deal' => false,
                'is_popular' => true,
                'is_new' => false,
                'is_recommended' => true,
            ],
            [
                'category_slug' => 'supermarche',
                'name' => 'Carton Huile Végétale Dinor 5L x 4',
                'vendor_name' => 'Alimentation Centrale ACI',
                'reference' => 'BKO-SP-002',
                'price' => 24500,
                'original_price' => 28000,
                'discount_percent' => 12,
                'badge' => 'Offre Spéciale',
                'stock' => 30,
                'rating' => 4.8,
                'reviews_count' => 38,
                'image_url' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Huile de table 100% végétale raffinée sans cholestérol, enrichie en vitamine A. Indispensable pour toutes vos fritures et préparations culinaires.',
                'features' => [
                    'Volume' => '4 bidons de 5 Litres (Total 20L)',
                    'Conservation' => 'À l\'abri de la lumière',
                ],
                'is_flash_deal' => true,
                'flash_deal_ends_at' => now()->addDays(2)->setHour(23)->setMinute(59),
                'is_popular' => true,
                'is_new' => false,
                'is_recommended' => true,
            ],
            [
                'category_slug' => 'supermarche',
                'name' => 'Boîte de Lait en Poudre Nido Fortifié 2.5kg',
                'vendor_name' => 'Supermarché Azar Express',
                'reference' => 'BKO-SP-003',
                'price' => 16500,
                'original_price' => 18000,
                'discount_percent' => 8,
                'badge' => 'Famille',
                'stock' => 50,
                'rating' => 4.9,
                'reviews_count' => 42,
                'image_url' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Lait entier en poudre enrichi en fer, zinc, vitamines A, C et D. Parfait pour les petits déjeuners et la préparation des entremets familiaux.',
                'features' => [
                    'Poids net' => '2 500 g (2.5 kg)',
                    'Emballage' => 'Boîte métallique hermétique',
                ],
                'is_flash_deal' => false,
                'is_popular' => true,
                'is_new' => false,
                'is_recommended' => false,
            ],
            [
                'category_slug' => 'supermarche',
                'name' => 'Thé Vert de Chine Achoura Extra Spécial (Carton de 10)',
                'vendor_name' => 'Maison du Thé Bamako',
                'reference' => 'BKO-SP-004',
                'price' => 11000,
                'original_price' => 13500,
                'discount_percent' => 18,
                'badge' => '-18%',
                'stock' => 60,
                'rating' => 5.0,
                'reviews_count' => 88,
                'image_url' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Le véritable thé vert gunpowder Gunpowder Flecha Achoura pour préparer un authentique Baroda (thé malien) bien mousseux. Feuilles entières triées avec soin.',
                'features' => [
                    'Conditionnement' => '10 boîtes individuelles de 250g',
                    'Qualité' => 'Spécial Gunpowder 3505',
                ],
                'is_flash_deal' => true,
                'flash_deal_ends_at' => now()->addDays(1)->setHour(20)->setMinute(0),
                'is_popular' => true,
                'is_new' => false,
                'is_recommended' => true,
            ],

            // High-Tech
            [
                'category_slug' => 'high-tech',
                'name' => 'Smartphone Samsung Galaxy A55 5G (128GB / 8GB RAM)',
                'vendor_name' => 'BKO Tech Store ACI 2000',
                'reference' => 'BKO-HT-101',
                'price' => 215000,
                'original_price' => 245000,
                'discount_percent' => 12,
                'badge' => 'Offre Flash',
                'stock' => 12,
                'rating' => 4.9,
                'reviews_count' => 29,
                'image_url' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1598327105666-5b89351aff97?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Écran Super AMOLED 120Hz immersif, appareil photo 50MP stabilisé avec vision nocturne, batterie ultra endurante 5000 mAh avec charge rapide 25W. Garantie officielle 1 an.',
                'features' => [
                    'Écran' => '6.6 pouces FHD+ Super AMOLED 120Hz',
                    'Stockage' => '128 Go extensible via MicroSD',
                    'RAM' => '8 Go',
                    'Réseau' => 'Double SIM 5G / 4G LTE Mali',
                ],
                'is_flash_deal' => true,
                'flash_deal_ends_at' => now()->addDays(3)->setHour(23)->setMinute(59),
                'is_popular' => true,
                'is_new' => true,
                'is_recommended' => true,
            ],
            [
                'category_slug' => 'high-tech',
                'name' => 'Écouteurs Sans Fil Oraimo FreePods 4 avec Réduction de Bruit',
                'vendor_name' => 'Oraimo Official Mali',
                'reference' => 'BKO-HT-102',
                'price' => 22000,
                'original_price' => 28000,
                'discount_percent' => 21,
                'badge' => '-21%',
                'stock' => 35,
                'rating' => 4.7,
                'reviews_count' => 53,
                'image_url' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Autonomie jusqu\'à 35 heures de musique, son riche et basses profondes HavyBass, suppression active du bruit et résistance aux éclaboussures IPX5.',
                'features' => [
                    'Connectivité' => 'Bluetooth 5.3 instantané',
                    'Autonomie' => '35.5 heures avec boîtier',
                    'Micro' => '4 micros avec réduction de bruit d\'appel',
                ],
                'is_flash_deal' => false,
                'is_popular' => true,
                'is_new' => false,
                'is_recommended' => true,
            ],
            [
                'category_slug' => 'high-tech',
                'name' => 'Smart TV 43" 4K UHD Smart Frameless avec Récepteur Intégré',
                'vendor_name' => 'Électro Bamako Médina',
                'reference' => 'BKO-HT-103',
                'price' => 145000,
                'original_price' => 170000,
                'discount_percent' => 15,
                'badge' => 'Promo BKO',
                'stock' => 8,
                'rating' => 4.8,
                'reviews_count' => 19,
                'image_url' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Profitez de vos chaînes préférées et de YouTube / Netflix en résolution 4K Ultra Haute Définition. Design ultra fin sans bordure pour sublimer votre salon.',
                'features' => [
                    'Taille écran' => '43 pouces (108 cm)',
                    'Résolution' => '3840 x 2160 Pixels 4K UHD',
                    'Système' => 'Android TV avec Google Play Store',
                ],
                'is_flash_deal' => false,
                'is_popular' => false,
                'is_new' => true,
                'is_recommended' => true,
            ],

            // Mode
            [
                'category_slug' => 'mode',
                'name' => 'Complet Bazin Riche Getzner Teinté Artisanal (5 Mètres)',
                'vendor_name' => 'Atelier Modibo Bazin Badalabougou',
                'reference' => 'BKO-MD-201',
                'price' => 45000,
                'original_price' => 55000,
                'discount_percent' => 18,
                'badge' => 'Artisanat d\'Exception',
                'stock' => 15,
                'rating' => 5.0,
                'reviews_count' => 31,
                'image_url' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Authentique tissu Bazin riche première qualité, teinté à la main par les maîtres teinturiers de Bamako. Brillance éclatante, rigidité noble et tenue impeccable pour les grandes cérémonies.',
                'features' => [
                    'Métrage' => 'Coupe de 5 mètres',
                    'Matière' => '100% Coton peigné Getzner',
                    'Couleur' => 'Indigo profond et motifs exclusifs',
                ],
                'is_flash_deal' => false,
                'is_popular' => true,
                'is_new' => true,
                'is_recommended' => true,
            ],
            [
                'category_slug' => 'mode',
                'name' => 'Chaussures Mocassins Homme Cuir Véritable Cousu Main',
                'vendor_name' => 'Maroquinerie du Fleuve',
                'reference' => 'BKO-MD-202',
                'price' => 28000,
                'original_price' => 35000,
                'discount_percent' => 20,
                'badge' => '-20%',
                'stock' => 20,
                'rating' => 4.6,
                'reviews_count' => 17,
                'image_url' => 'https://images.unsplash.com/photo-1533867617858-e7b97e060509?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1533867617858-e7b97e060509?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Élégants mocassins en cuir souple pleine fleur. Semelle intérieure respirante et extérieure anti-dérapante garantissant confort optimal lors de vos journées actives.',
                'features' => [
                    'Tailles disponibles' => '40 à 45',
                    'Matière' => 'Cuir de veau traité',
                ],
                'is_flash_deal' => false,
                'is_popular' => false,
                'is_new' => true,
                'is_recommended' => false,
            ],

            // Beauté
            [
                'category_slug' => 'beaute',
                'name' => 'Pot Pur Beurre de Karité Bio du Mali 500g Non Raffiné',
                'vendor_name' => 'Coopérative Karité Mandé',
                'reference' => 'BKO-BT-301',
                'price' => 4500,
                'original_price' => 6000,
                'discount_percent' => 25,
                'badge' => '100% Naturel',
                'stock' => 80,
                'rating' => 4.9,
                'reviews_count' => 74,
                'image_url' => 'https://images.unsplash.com/photo-1608248597359-58b27f42c262?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1608248597359-58b27f42c262?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Beurre de karité pur vierge produit artisanalement par pression à froid. Idéal pour nourrir en profondeur la peau sèche, protéger des rayons solaires et revitaliser les cheveux crépus ou frisés.',
                'features' => [
                    'Contenance' => '500 grammes',
                    'Certification' => 'Origine Mali 100% bio sans additif',
                ],
                'is_flash_deal' => false,
                'is_popular' => true,
                'is_new' => false,
                'is_recommended' => true,
            ],

            // Maison & Climatisation
            [
                'category_slug' => 'maison',
                'name' => 'Ventilateur Rechargeable Solaire 16" avec Port USB & Lampe LED',
                'vendor_name' => 'Mali Énergie & Confort',
                'reference' => 'BKO-MS-401',
                'price' => 38000,
                'original_price' => 45000,
                'discount_percent' => 15,
                'badge' => 'Essentiel Chaleur',
                'stock' => 25,
                'rating' => 4.9,
                'reviews_count' => 46,
                'image_url' => 'https://images.unsplash.com/photo-1618941716939-553df3c6c278?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1618941716939-553df3c6c278?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'L\'appareil incontournable à Bamako pour rester au frais même en cas de coupure de courant. Batterie lithium rechargeable, panneau solaire inclus et fonction powerbank pour recharger les téléphones.',
                'features' => [
                    'Autonomie' => 'Jusqu\'à 12 heures en vitesse moyenne',
                    'Alimentation' => 'Solaire 15W + Secteur 220V',
                    'Hauteur' => 'Réglable de 110 à 135 cm',
                ],
                'is_flash_deal' => true,
                'flash_deal_ends_at' => now()->addDays(2)->setHour(22)->setMinute(0),
                'is_popular' => true,
                'is_new' => true,
                'is_recommended' => true,
            ],

            // Fruits & Légumes
            [
                'category_slug' => 'fruits-legumes',
                'name' => 'Panier Fraîcheur Maraîchère Locale (Tomates, Oignons, Piments) 5kg',
                'vendor_name' => 'Producteurs Réunis de Baguineda',
                'reference' => 'BKO-FL-501',
                'price' => 6500,
                'original_price' => 8000,
                'discount_percent' => 18,
                'badge' => 'Récolté ce matin',
                'stock' => 40,
                'rating' => 4.8,
                'reviews_count' => 23,
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Sélection de légumes frais cueillis le matin même dans la ceinture maraîchère de Baguineda et acheminés le jour même pour vos sauces et repas.',
                'features' => [
                    'Contenu' => '2kg Tomates fermes, 2kg Oignons doux, 500g Concombres, Piments',
                    'Livraison' => 'Emballage carton aéré fraîcheur',
                ],
                'is_flash_deal' => false,
                'is_popular' => true,
                'is_new' => false,
                'is_recommended' => true,
            ],
            [
                'category_slug' => 'fruits-legumes',
                'name' => 'Carton de Mangues Kent d\'Exportation de Sikasso (7kg)',
                'vendor_name' => 'Vergers du Sud Mali',
                'reference' => 'BKO-FL-502',
                'price' => 9000,
                'original_price' => 11000,
                'discount_percent' => 18,
                'badge' => 'Saison Sikasso',
                'stock' => 30,
                'rating' => 5.0,
                'reviews_count' => 58,
                'image_url' => 'https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Mangues charnues et juteuses sans fibres, au goût sucré incomparable. Sélection calibre premium récolté à point.',
                'features' => [
                    'Variété' => 'Mangue Kent calibre 9/10',
                    'Poids net' => 'Carton de 7 kg',
                ],
                'is_flash_deal' => true,
                'flash_deal_ends_at' => now()->addDays(1)->setHour(18)->setMinute(0),
                'is_popular' => true,
                'is_new' => true,
                'is_recommended' => true,
            ],
        ];

        foreach ($productsData as $pData) {
            $catSlug = $pData['category_slug'];
            unset($pData['category_slug']);
            $pData['category_id'] = $createdCategories[$catSlug]->id;
            $pData['slug'] = Str::slug($pData['name']);

            Product::create($pData);
        }

        // Créer des commandes exemples pour alimenter l'interface admin
        $sampleOrder = Order::create([
            'order_number' => 'BKO-' . date('Y') . '-1082',
            'customer_first_name' => 'Oumar',
            'customer_last_name' => 'Traoré',
            'customer_phone' => '+223 76 45 12 89',
            'customer_email' => 'oumar.traore@gmail.com',
            'city' => 'Bamako',
            'neighborhood' => 'ACI 2000',
            'address' => 'Près de l\'Immeuble BMS, Porte 342',
            'delivery_notes' => 'Appeler à l\'arrivée au portail gris.',
            'payment_method' => 'orange_money',
            'orange_money_number' => '+223 76 45 12 89',
            'payment_status' => 'paid',
            'subtotal' => 44000,
            'delivery_fee' => 1500,
            'discount' => 0,
            'total' => 45500,
            'status' => 'in_delivery',
        ]);

        $firstProduct = Product::first();
        if ($firstProduct) {
            OrderItem::create([
                'order_id' => $sampleOrder->id,
                'product_id' => $firstProduct->id,
                'product_name' => $firstProduct->name,
                'product_image' => $firstProduct->image_url,
                'price' => $firstProduct->price,
                'quantity' => 2,
                'total' => $firstProduct->price * 2,
            ]);
        }

        $sampleOrder2 = Order::create([
            'order_number' => 'BKO-' . date('Y') . '-1083',
            'customer_first_name' => 'Fatoumata',
            'customer_last_name' => 'Coulibaly',
            'customer_phone' => '+223 66 98 44 11',
            'customer_email' => 'fatou.coulibaly@yahoo.fr',
            'city' => 'Bamako',
            'neighborhood' => 'Badalabougou',
            'address' => 'Rue 12, derrière l\'Ambassade d\'Allemagne',
            'delivery_notes' => 'Paiement en espèces prévu avec appoint de 50 000 FCFA.',
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'pending',
            'subtotal' => 38000,
            'delivery_fee' => 1500,
            'discount' => 0,
            'total' => 39500,
            'status' => 'confirmed',
        ]);
    }
}
