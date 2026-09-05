Tu es un développeur Laravel senior spécialisé en e-commerce.

## CONTEXTE IMPORTANT

Mon site web est **déjà un site e-commerce fonctionnel qui vend des produits physiques**.

Je ne veux PAS transformer le site en une boutique exclusivement numérique.

Je veux **AJOUTER une nouvelle fonctionnalité de vente de produits numériques** tout en conservant intégralement le système actuel de vente de produits physiques.

Le site devra donc gérer simultanément :

### Produits physiques
- Produits actuellement vendus sur le site
- Stock
- Quantité
- Livraison
- Adresse de livraison
- Commandes physiques
- Frais de livraison
- etc.

### Produits numériques
- Ebook
- Livre PDF
- Vidéo
- Formation vidéo
- Audio
- Documents
- Fichiers ZIP
- Logiciels
- Packs numériques

**NE PAS casser, remplacer ou réécrire le système actuel de produits physiques.**

---

# 1. ANALYSE DU SYSTÈME EXISTANT

Avant toute modification, analyse complètement le projet existant.

Identifie :

- Framework et version
- Architecture Laravel
- Modèles existants
- Table `products` ou équivalent
- Catégories
- Panier
- Checkout
- Commandes
- `order_items`
- Paiement
- Gestion du stock
- Livraison
- Utilisateurs
- Authentification
- Emails
- Notifications
- Upload de fichiers
- Storage
- Administration
- Livewire si utilisé
- Design system existant

Ne crée pas une architecture parallèle si une architecture existante peut être étendue proprement.

---

# 2. PRINCIPE CENTRAL

Le modèle de produit existant doit pouvoir distinguer :

```text
PHYSICAL
DIGITAL
```

Par exemple :

```text
product_type = physical
```

pour les produits actuels.

Et :

```text
product_type = digital
```

pour les nouveaux produits numériques.

Si le projet possède déjà un champ permettant de distinguer les types de produits, réutilise-le plutôt que d'en créer un nouveau.

---

# 3. NE PAS MODIFIER LE PARCOURS DES PRODUITS PHYSIQUES

Le fonctionnement actuel doit rester identique.

Pour un produit physique :

```text
Produit
 ↓
Panier
 ↓
Checkout
 ↓
Adresse
 ↓
Livraison
 ↓
Paiement
 ↓
Commande
 ↓
Préparation
 ↓
Livraison
```

Aucune régression ne doit être introduite.

---

# 4. NOUVEAU PARCOURS POUR UN PRODUIT NUMÉRIQUE

Pour un produit numérique :

```text
Produit numérique
 ↓
Panier
 ↓
Checkout
 ↓
Paiement
 ↓
Confirmation du paiement
 ↓
Commande PAID
 ↓
Attribution automatique du produit
 ↓
Email au client
 ↓
Espace client
 ↓
Mes téléchargements
```

Il ne faut PAS demander d'adresse de livraison pour une commande composée uniquement de produits numériques.

---

# 5. COMMANDES MIXTES

Le système doit également gérer une commande contenant :

```text
Produit physique
+
Produit numérique
```

Exemple :

```text
Livre physique       15 000 FCFA
Ebook                 5 000 FCFA
------------------------------
Sous-total           20 000 FCFA
Livraison             2 000 FCFA
------------------------------
Total                22 000 FCFA
```

Dans ce cas :

- Le produit physique suit le processus normal de livraison.
- Le produit numérique est immédiatement disponible après paiement.
- Le client reçoit les accès numériques sans attendre la livraison physique.

Le système doit donc distinguer les éléments physiques et numériques d'une même commande.

---

# 6. PRODUITS NUMÉRIQUES DANS LE BACK-OFFICE

Ajouter dans l'administration la possibilité de créer un produit numérique.

Lors de la création :

```text
Type de produit

○ Produit physique
○ Produit numérique
```

Si :

```text
Produit physique
```

afficher les champs existants :

- Stock
- Poids
- Dimensions
- Livraison
- etc.

Si :

```text
Produit numérique
```

afficher :

- Type numérique
- Fichier(s)
- Taille
- Format
- Durée si vidéo
- Nombre de pages si ebook
- Accès / téléchargement
- Limite de téléchargements
- Durée de validité éventuelle

---

# 7. TYPES DE PRODUITS NUMÉRIQUES

Prévoir :

```text
ebook
pdf
video
audio
software
zip
course
other
```

L'administrateur doit pouvoir choisir le type.

---

# 8. GESTION DES FICHIERS

Un produit numérique peut avoir plusieurs fichiers.

Exemple :

### Ebook

```text
Mon-livre.pdf
```

### Formation vidéo

```text
01-introduction.mp4
02-module-1.mp4
03-module-2.mp4
support.pdf
```

### Pack

```text
guide.pdf
template.xlsx
bonus.zip
```

Créer une relation :

```text
Product
    ↓
ProductFiles
```

Ne pas exposer directement les fichiers payants dans `public/`.

Utiliser le système Storage sécurisé de Laravel ou l'architecture de stockage déjà présente.

---

# 9. TÉLÉCHARGEMENT SÉCURISÉ

Un client ne doit pouvoir télécharger un fichier que s'il possède réellement le produit.

Lorsqu'il clique sur :

```text
Télécharger
```

le serveur doit vérifier :

1. utilisateur connecté
2. commande existante
3. commande payée
4. produit présent dans la commande
5. fichier appartenant au produit
6. téléchargement autorisé

Puis générer une URL sécurisée/signée ou servir le fichier via une route protégée.

Ne jamais permettre :

```text
/storage/products/ebook.pdf
```

directement pour les fichiers payants.

---

# 10. LIMITATION DES TÉLÉCHARGEMENTS

Prévoir éventuellement :

```text
download_limit
```

Exemples :

```text
Illimité
5 téléchargements
10 téléchargements
```

Et enregistrer :

```text
user
product
file
order
date
IP
```

Chaque téléchargement doit être journalisé.

---

# 11. ESPACE CLIENT

Ajouter une nouvelle section dans l'espace client existant :

```text
Mes produits numériques
```

Exemple :

```text
Mes achats numériques

┌──────────────────────────────┐
│ 📚 Guide Marketing Digital   │
│ Acheté le 05/09/2026         │
│                              │
│ [Télécharger]                │
└──────────────────────────────┘
```

Pour une formation vidéo :

```text
🎥 Formation Laravel

[Accéder à la formation]
```

---

# 12. EMAIL AUTOMATIQUE APRÈS PAIEMENT

Une fois le paiement réellement confirmé côté serveur, envoyer automatiquement un email au client.

IMPORTANT :

Ne pas envoyer l'email simplement parce que le client arrive sur une page `/success`.

L'envoi doit être déclenché après confirmation réelle du paiement.

L'email doit contenir les informations de la commande.

Exemple :

```text
Bonjour {{customer_name}},

Merci pour votre achat.

Votre commande {{order_number}} a bien été confirmée.

Produits numériques achetés :

{{digital_products}}

Montant total : {{total}} FCFA

Vos produits numériques sont maintenant disponibles dans votre espace client.

[ACCÉDER À MES ACHATS]

Vous pouvez retrouver vos produits à tout moment dans votre compte.

Merci pour votre confiance.

{{site_name}}
```

Pour les commandes mixtes, l'email peut également indiquer :

```text
Votre commande contient également des produits physiques.
Ils seront traités et livrés selon le processus habituel.
```

---

# 13. EMAIL ADMINISTRATEUR

Lorsqu'un achat numérique est confirmé, envoyer également une notification à l'administrateur.

Informations :

```text
Nouvelle vente

Commande : CMD-XXXX

Client :
Nom
Email
Téléphone

Produit :
Nom du produit

Montant :
XX FCFA

Mode de paiement :
XXXX

Référence paiement :
XXXX
```

---

# 14. EMAILS AVEC QUEUE

Utiliser les Jobs/Queues Laravel pour les emails.

Ne pas ralentir la réponse du checkout à cause de l'envoi d'emails.

Exemple :

```text
OrderPaid
   ↓
Job
   ↓
Email client
   ↓
Email administrateur
```

---

# 15. PAGE PRODUIT

Adapter la page produit existante afin que les produits numériques soient présentés correctement.

Pour un ebook :

```text
📚 Ebook

Titre

Description

Format : PDF
Taille : 12 MB
Pages : 180

5 000 FCFA

[ACHETER]
```

Pour une vidéo :

```text
🎥 Formation vidéo

Durée : 4h30
Format : MP4
Accès : immédiat après paiement

25 000 FCFA

[ACHETER]
```

Conserver exactement la charte graphique actuelle du site.

---

# 16. PANIER

Le panier existant doit continuer à fonctionner.

Ajouter simplement la logique permettant de déterminer :

```text
physical_items
digital_items
```

Exemple :

```text
Panier

Produits physiques
------------------
T-shirt          10 000
Livre physique   15 000

Produits numériques
-------------------
Ebook             5 000
Formation        20 000

Sous-total       50 000
Livraison         2 000

TOTAL            52 000
```

La livraison ne doit être calculée que selon les règles existantes pour les produits physiques.

Si le panier contient uniquement des produits numériques :

```text
Livraison = 0
```

et aucune adresse de livraison ne doit être exigée.

---

# 17. CHECKOUT INTELLIGENT

Le checkout doit détecter automatiquement le contenu du panier.

### Panier 100 % physique

Conserver le checkout actuel.

### Panier 100 % numérique

Masquer :

- Adresse de livraison
- Transporteur
- Frais de livraison
- Informations inutiles à la livraison

### Panier mixte

Afficher :

- Informations client
- Adresse de livraison pour le physique
- Produits numériques immédiatement disponibles après paiement

---

# 18. COMMANDES EXISTANTES

NE PAS casser les commandes historiques.

Les anciennes commandes doivent continuer à fonctionner.

Faire évoluer la structure de manière rétrocompatible.

Avant migration :

```text
backup
```

Puis migrations Laravel propres.

---

# 19. ADMIN — FILTRES

Dans les commandes, ajouter :

```text
Toutes
Physiques
Numériques
Mixtes
Payées
En attente
Expédiées
Terminées
```

Cela permettra de distinguer facilement les commandes.

---

# 20. STATISTIQUES

Ajouter dans le dashboard existant :

```text
Ventes physiques
Ventes numériques
Ventes mixtes
```

Et :

```text
CA produits physiques
CA produits numériques
CA total
```

Ajouter également :

```text
Produits numériques les plus vendus
Téléchargements
```

---

# 21. FACTURATION

Les produits numériques doivent apparaître normalement sur les factures existantes.

Exemple :

```text
Produit                  Qté     Prix

Livre physique            1     15 000
Ebook                     1      5 000
```

La facture doit rester compatible avec les commandes physiques.

---

# 22. DROITS D'ACCÈS

Créer une logique de possession :

```text
User
 ↓
Order
 ↓
OrderItem
 ↓
Product
```

Un utilisateur qui possède un produit numérique doit pouvoir y accéder depuis son compte.

Ne pas créer nécessairement une nouvelle table `purchases` si `orders/order_items` permettent déjà de déterminer la propriété.

Créer une table dédiée uniquement si l'architecture existante le justifie.

---

# 23. ARCHITECTURE

Adapter l'architecture actuelle plutôt que la remplacer.

Exemple potentiel :

```text
Product
├── type
│   ├── physical
│   └── digital
│
└── ProductFile
```

Puis :

```text
Order
└── OrderItem
      └── Product
            └── ProductFile
```

Créer éventuellement :

```text
Download
```

pour tracer les téléchargements.

---

# 24. SÉCURITÉ

Respecter strictement :

- Authorization
- Policies
- CSRF
- Validation serveur
- Rate limiting
- URLs signées
- Protection des fichiers
- Vérification de propriété
- Protection contre le téléchargement direct
- Logs
- Vérification serveur du paiement

---

# 25. TESTS DE NON-RÉGRESSION

Avant de terminer, tester obligatoirement :

### Produit physique

```text
Ajout panier
→ Checkout
→ Livraison
→ Paiement
→ Commande
```

### Produit numérique

```text
Ajout panier
→ Checkout
→ Paiement
→ Email
→ Téléchargement
```

### Commande mixte

```text
Physique + numérique
→ Checkout
→ Livraison physique
→ Accès numérique immédiat
```

### Anciennes commandes

Vérifier qu'elles fonctionnent toujours.

---

# 26. RÈGLE ABSOLUE

Tu dois considérer le système actuel comme **une application en production**.

Donc :

❌ Ne pas refaire le e-commerce.

❌ Ne pas remplacer le système de commande.

❌ Ne pas remplacer le panier.

❌ Ne pas remplacer le checkout.

❌ Ne pas supprimer les fonctionnalités physiques.

❌ Ne pas créer une deuxième boutique indépendante.

✅ Étendre le système existant.

✅ Réutiliser les modèles existants.

✅ Réutiliser le panier existant.

✅ Réutiliser le checkout existant.

✅ Réutiliser le paiement existant.

✅ Ajouter uniquement la logique nécessaire aux produits numériques.

---

# 27. MÉTHODE DE TRAVAIL

Travaille en plusieurs étapes.

### ÉTAPE 1

Analyser le code existant.

### ÉTAPE 2

Identifier les modèles, migrations, services et composants à modifier.

### ÉTAPE 3

Présenter un plan d'implémentation court.

### ÉTAPE 4

Implémenter les migrations.

### ÉTAPE 5

Implémenter la gestion des produits numériques.

### ÉTAPE 6

Implémenter les fichiers sécurisés.

### ÉTAPE 7

Adapter panier + checkout.

### ÉTAPE 8

Adapter commandes.

### ÉTAPE 9

Ajouter les droits d'accès.

### ÉTAPE 10

Ajouter les téléchargements.

### ÉTAPE 11

Ajouter les emails automatiques.

### ÉTAPE 12

Ajouter l'espace client.

### ÉTAPE 13

Ajouter les fonctionnalités administrateur.

### ÉTAPE 14

Tester les scénarios physique / numérique / mixte.

### ÉTAPE 15

Vérifier qu'aucune fonctionnalité existante n'a été cassée.

Ne commence pas par réécrire le projet. **Étends proprement l'existant.**