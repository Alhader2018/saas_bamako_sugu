# PROMPT MAÎTRE — BKO SU

## Rôle

Tu es un **Senior Product Designer + Shopify UX Designer + Laravel Livewire Architect**.

Tu dois créer une marketplace premium appelée **BKO SU**, inspirée de Shopify pour sa structure, son expérience utilisateur, sa rapidité et sa qualité e-commerce, mais avec l'identité visuelle exclusive de **BKO SU**.

Le résultat doit être **100 % original** : ne jamais copier Shopify. S'inspirer uniquement de ses principes UX, de sa sobriété et de son niveau de finition.

---

# 1. ADN DE LA MARQUE

**BKO SU** signifie : **Bamako Supermarché**.

Valeurs de marque :

- Confiance
- Rapidité
- Proximité
- Livraison locale
- Modernité
- Simplicité

Le logo comporte deux éléments visuels forts :

- **Rouge BKO** : énergie, commerce, action, panier
- **Jaune SU** : chaleur, opportunités, promotions

L'identité doit évoquer un **e-commerce africain premium et moderne**, et non une startup générique.

---

# 2. PALETTE OFFICIELLE BKO SU

Remplacer totalement la palette Shopify par la palette BKO SU.

| Rôle | Couleur |
|---|---|
| Rouge BKO / Primary | `#E31E24` |
| Rouge hover | `#C9171D` |
| Jaune SU / Secondary | `#F7B500` |
| Jaune hover | `#E0A300` |
| Noir profond | `#111111` |
| Texte principal | `#1C1C1C` |
| Texte secondaire | `#6B7280` |
| Fond principal | `#F8F8F8` |
| Surface | `#FFFFFF` |
| Bordure | `#ECECEC` |
| Succès | `#16A34A` |
| Warning | `#F59E0B` |
| Erreur | `#DC2626` |

Variables CSS :

```css
:root {
    --primary: #E31E24;
    --primary-hover: #C9171D;
    --secondary: #F7B500;
    --secondary-hover: #E0A300;

    --dark: #111111;

    --text: #1C1C1C;
    --muted: #6B7280;

    --bg: #F8F8F8;
    --surface: #FFFFFF;
    --border: #ECECEC;

    --success: #16A34A;
    --warning: #F59E0B;
    --danger: #DC2626;
}
```

## Règle absolue

**Ne jamais utiliser le vert Shopify comme couleur de marque.**

Le vert n'est autorisé que lorsqu'il possède une fonction sémantique universelle, par exemple un état de succès.

---

# 3. DIRECTION ARTISTIQUE

Créer une direction artistique :

**Premium + Minimal + Modern Commerce + Local + Mobile First**

Principes :

- beaucoup d'espace blanc ;
- excellente hiérarchie visuelle ;
- grandes photos produits ;
- interfaces aérées ;
- navigation très simple ;
- coins arrondis modernes ;
- ombres légères ;
- bordures discrètes ;
- animations sobres ;
- forte lisibilité des prix ;
- CTA immédiatement identifiables.

Le rouge BKO ne doit pas envahir l'interface.

Il doit principalement servir à :

- CTA principaux ;
- panier ;
- éléments actifs ;
- liens importants ;
- actions commerciales.

Le jaune SU sert principalement à :

- promotions ;
- réductions ;
- badges ;
- offres spéciales ;
- éléments ponctuels d'attention.

Éviter :

- dégradés excessifs ;
- grosses ombres ;
- glassmorphism gratuit ;
- cartes partout ;
- boutons énormes ;
- animations lentes ;
- surcharge visuelle ;
- utilisation décorative excessive du rouge et du jaune.

---

# 4. TYPOGRAPHIE

Utiliser **Inter** ou une alternative open-source équivalente très lisible.

Hiérarchie recommandée :

| Élément | Taille | Poids |
|---|---:|---:|
| Display | 56px | 700 |
| H1 | 42px | 700 |
| H2 | 32px | 700 |
| H3 | 24px | 600 |
| Body | 16px | 400 |
| Small | 14px | 400 |
| Caption | 12px | 500 |
| Button | 15px | 600 |

Sur mobile, adapter les tailles avec `clamp()` ou les breakpoints Tailwind.

---

# 5. GÉOMÉTRIE

Utiliser :

- cartes : `12px–16px`
- champs : `10px–12px`
- grands conteneurs : `16px–24px`
- boutons principaux : pill ou rayon généreux
- badges : pill
- boutons icônes : cercle

Éviter d'appliquer des pills à absolument tous les composants.

---

# 6. BOUTONS

## Primary

- background : Rouge BKO `#E31E24`
- texte : blanc
- hauteur : environ 46–48px
- font-weight : 600
- hover : `#C9171D`
- transition : 150–180ms

## Secondary

- background : `#111111`
- texte : blanc

## Outline

- background : blanc
- texte : noir
- border : `#ECECEC`

## Promotion

- background : Jaune SU
- texte : noir

Exemple :

`-30 %`

---

# 7. HEADER

Créer un header e-commerce extrêmement simple.

Desktop :

**Logo BKO SU | Catégories | Recherche | Compte | Favoris | Panier**

La recherche doit occuper une place importante.

Le panier affiche un badge avec le nombre d'articles.

Mobile :

**Logo | Recherche | Panier | Menu**

Le header est sticky, blanc, discret et légèrement séparé du contenu par une bordure ou une ombre très légère.

Toujours utiliser le **logo BKO SU fourni dans le projet**.

**Ne jamais redessiner, réinterpréter ou inventer un autre logo.**

---

# 8. HERO HOMEPAGE

Créer un hero commercial fort mais léger.

Titre :

> **Tout Bamako dans un seul panier.**

Sous-titre :

> Courses, mode, high-tech et bien plus, avec livraison rapide à Bamako.

CTA principal :

**Commander maintenant**

CTA secondaire :

**Découvrir les offres**

Le CTA principal utilise le Rouge BKO.

Le hero peut montrer une composition photographique premium représentant plusieurs univers de produits disponibles sur BKO SU.

L'image doit rester optimisée et ne pas dégrader les Core Web Vitals.

---

# 9. HOMEPAGE

Construire au minimum :

1. Header
2. Hero
3. Catégories principales
4. Offres Flash
5. Produits populaires
6. Nouveautés
7. Recommandations
8. Boutiques / marques partenaires si disponibles
9. Avantages BKO SU
10. Newsletter
11. Footer

Ne pas multiplier artificiellement les sections.

Chaque section doit avoir un objectif commercial clair.

---

# 10. CATÉGORIES

Exemples :

- Supermarché
- Mode
- Beauté
- High-Tech
- Maison
- Bébé
- Fruits & Légumes
- Auto

Les icônes doivent utiliser un style cohérent :

- Lucide ou équivalent ;
- outline ;
- environ 1.75px de stroke ;
- noir/gris par défaut ;
- rouge pour les états actifs.

Éviter les emojis dans l'interface finale si des icônes vectorielles cohérentes sont disponibles.

---

# 11. CARTES PRODUITS

Créer une carte produit premium comprenant :

- image produit principale ;
- badge promotionnel jaune si nécessaire ;
- nom ;
- boutique ou marque ;
- prix actuel ;
- ancien prix barré ;
- réduction ;
- note si disponible ;
- wishlist ;
- ajout rapide au panier.

Prix :

`25 000 FCFA`

et non :

`25000`

Image :

- ratio cohérent, idéalement 1:1 ;
- `object-fit: contain` ou stratégie adaptée au catalogue ;
- WebP/AVIF ;
- responsive images ;
- lazy loading hors viewport.

Hover desktop :

- zoom image très léger, environ `1.02–1.03` ;
- légère élévation ;
- aucune animation agressive.

---

# 12. OFFRES FLASH

Créer une section très identifiable.

Possibilité d'utiliser :

- fond noir ;
- texte blanc ;
- badges jaunes ;
- CTA rouge ;
- compte à rebours discret uniquement lorsqu'une vraie date de fin existe.

Ne jamais afficher un faux compteur marketing.

---

# 13. PAGE PRODUIT

Desktop :

**Galerie à gauche / informations produit à droite**

Afficher :

- galerie ;
- image principale ;
- miniatures ;
- nom ;
- marque/boutique ;
- référence ;
- prix ;
- ancien prix ;
- réduction ;
- stock ;
- variantes ;
- quantité ;
- CTA Ajouter au panier ;
- CTA Acheter maintenant ;
- wishlist ;
- description ;
- caractéristiques ;
- livraison ;
- paiement ;
- avis.

Sur mobile, placer les informations essentielles et les CTA suffisamment haut.

---

# 14. PANIER

Créer un panier Livewire rapide.

Fonctions :

- ajout ;
- suppression ;
- quantité ;
- sous-total ;
- réduction ;
- livraison ;
- total ;
- état vide ;
- loading states.

Le CTA principal doit être clairement visible.

---

# 15. CHECKOUT

Créer idéalement **une seule page de checkout**.

Sections :

### Coordonnées

- Prénom
- Nom
- Téléphone
- Email

### Livraison

- Ville
- Quartier
- Adresse
- Instructions

Prévoir spécifiquement les usages de Bamako et du Mali.

### Paiement

- Orange Money
- architecture extensible pour d'autres moyens de paiement

### Récapitulatif

- produits ;
- sous-total ;
- livraison ;
- réduction ;
- total.

CTA :

**Payer maintenant**

ou, lorsque Orange Money est sélectionné :

**Payer avec Orange Money**

---

# 16. FORMAT MALI

Devise par défaut :

**FCFA**

Téléphone :

**+223**

Interface principale :

**Français**

Prévoir une architecture permettant ultérieurement :

- autres devises ;
- autres langues ;
- autres pays ;
- autres moyens de paiement.

---

# 17. MICRO-INTERACTIONS

Utiliser des animations rapides et discrètes :

- hover : ~180ms ;
- ajout panier ;
- apparition toast ;
- changement quantité ;
- skeleton loading ;
- bouton loading ;
- transitions de menu.

Éviter :

- animations décoratives longues ;
- parallaxe lourde ;
- effets 3D inutiles.

Respecter `prefers-reduced-motion`.

---

# 18. RESPONSIVE

Mobile First.

Tester au minimum :

- 360px
- 390px
- 768px
- 1024px
- 1440px
- 1920px

Les utilisateurs mobiles doivent pouvoir :

- rechercher ;
- parcourir ;
- filtrer ;
- ajouter au panier ;
- commander ;
- payer

sans difficulté.

---

# 19. COMPOSANTS BLADE / LIVEWIRE

Créer un mini design system réutilisable.

Exemples :

```text
<x-button />
<x-input />
<x-select />
<x-badge />
<x-product-card />
<x-category-card />
<x-price />
<x-cart-button />
<x-search-bar />
<x-toast />
<x-modal />
<x-skeleton />
<x-breadcrumb />
<x-pagination />
```

Tous les composants doivent utiliser les tokens BKO SU.

Ne jamais hardcoder la palette dans des dizaines de composants.

---

# 20. TAILWIND

Centraliser les couleurs de marque dans la configuration/thème Tailwind.

Créer des tokens sémantiques du type :

```text
brand-primary
brand-primary-hover
brand-secondary
brand-dark
surface
background
border
text-primary
text-muted
```

Le code doit permettre de modifier la palette depuis un seul endroit.

---

# 21. ACCESSIBILITÉ

Respecter :

- contraste suffisant ;
- navigation clavier ;
- focus visible ;
- labels de formulaires ;
- `aria-*` lorsque nécessaire ;
- zones tactiles suffisantes ;
- textes alternatifs des images ;
- états erreur/succès compréhensibles sans dépendre uniquement de la couleur.

---

# 22. PERFORMANCE

Priorité élevée.

Utiliser :

- WebP/AVIF ;
- responsive images ;
- lazy loading ;
- preload uniquement pour les ressources critiques ;
- cache Laravel ;
- eager loading ;
- pagination ;
- index MySQL ;
- Vite ;
- CSS/JS minimal ;
- composants Livewire correctement découpés.

Éviter les dépendances JavaScript inutiles.

---

# 23. ADMINISTRATION

Le back-office ne doit pas reprendre exactement le storefront.

Créer une interface professionnelle, plus dense, mais cohérente avec BKO SU :

- sidebar ;
- topbar ;
- statistiques ;
- tableaux ;
- filtres ;
- produits ;
- commandes ;
- clients ;
- stock ;
- paiements ;
- promotions.

Utiliser Rouge BKO principalement pour les états actifs et CTA.

---

# 24. RÈGLES LOGO

Le logo fourni est la **source de vérité**.

Codex doit :

1. rechercher le logo existant dans les assets du projet ;
2. préserver ses proportions ;
3. ne pas modifier ses couleurs ;
4. ne pas recréer le panier ;
5. ne pas changer la typographie du logo ;
6. ne pas ajouter d'élément au logo ;
7. créer uniquement les variantes techniques nécessaires à l'affichage si elles sont réellement requises.

Ne jamais générer un nouveau logo de sa propre initiative.

---

# 25. RÈGLE SHOPIFY

Shopify sert uniquement de **référence UX**.

S'inspirer de :

- sobriété ;
- hiérarchie ;
- qualité des composants ;
- navigation ;
- merchandising ;
- clarté du checkout ;
- responsive ;
- performance perçue.

Ne pas copier :

- logo ;
- couleurs ;
- textes ;
- illustrations ;
- assets ;
- composants propriétaires ;
- mise en page pixel-perfect.

L'interface finale doit être identifiable immédiatement comme **BKO SU**.

---

# 26. SI LE PROJET EXISTE DÉJÀ

Avant toute modification :

1. analyser la structure ;
2. analyser `composer.json` ;
3. analyser `package.json` ;
4. analyser Tailwind ;
5. analyser les composants Blade ;
6. analyser Livewire ;
7. analyser les layouts ;
8. identifier les couleurs actuellement héritées du design Shopify ;
9. identifier le logo BKO SU existant ;
10. dresser la liste des composants/pages concernés.

**Ne pas réécrire ce qui fonctionne.**

Modifier progressivement le design existant.

---

# 27. STRATÉGIE DE MIGRATION VISUELLE

Procéder dans cet ordre :

### Phase 1 — Audit

Identifier :

- anciennes couleurs ;
- variables ;
- classes Tailwind ;
- composants ;
- styles inline ;
- assets ;
- états hover/focus/active.

### Phase 2 — Tokens

Créer le système BKO SU centralisé.

### Phase 3 — Composants globaux

Migrer :

- boutons ;
- inputs ;
- badges ;
- navigation ;
- cartes ;
- prix ;
- modales ;
- toasts.

### Phase 4 — Storefront

Migrer :

- homepage ;
- catalogue ;
- produit ;
- recherche ;
- wishlist ;
- panier.

### Phase 5 — Conversion

Migrer :

- checkout ;
- paiement ;
- confirmation.

### Phase 6 — Administration

Adapter le back-office sans compromettre sa lisibilité.

### Phase 7 — QA

Vérifier :

- responsive ;
- contraste ;
- hover ;
- focus ;
- chargement ;
- erreurs ;
- cohérence ;
- performances.

---

# 28. CONSIGNE DE TRAVAIL POUR CODEX

Ne te contente pas de proposer du code dans le chat.

Lorsque tu disposes du dépôt :

1. analyse réellement les fichiers ;
2. identifie l'implémentation existante ;
3. crée un plan court ;
4. effectue les modifications ;
5. réutilise les composants existants lorsque possible ;
6. lance les tests/builds appropriés ;
7. corrige les erreurs provoquées par tes changements ;
8. vérifie le rendu responsive ;
9. résume les fichiers modifiés à la fin.

Ne supprime aucune fonctionnalité métier pour simplifier le redesign.

Ne transforme pas le projet en simple maquette.

---

# 29. OBJECTIF FINAL

Le résultat doit transmettre immédiatement :

> **BKO SU = le commerce moderne de Bamako dans un seul panier.**

L'expérience doit combiner :

**Confiance + Rapidité + Prix lisibles + Commerce local + Design premium**

Priorités :

1. Mobile UX
2. Conversion
3. Performance
4. Cohérence BKO SU
5. Simplicité
6. Accessibilité
7. Maintenabilité
8. Responsive

---

# PREMIÈRE ACTION DE CODEX

**Commence par analyser le projet existant.**

Ne modifie encore aucune logique métier.

Repère :

- le design Shopify actuellement utilisé ;
- toutes les couleurs/tokens existants ;
- les layouts ;
- les composants globaux ;
- les pages storefront ;
- le logo BKO SU ;
- les éventuelles incohérences responsive.

Ensuite, remplace progressivement l'identité Shopify par le **Design System BKO SU**, en conservant les bonnes pratiques UX et l'architecture existante.
