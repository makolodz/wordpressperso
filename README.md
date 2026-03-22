# 🎯 WP Landing Theme
 
Thème WordPress minimaliste conçu comme **terrain de jeu pour développeurs**.  
Une landing page simple avec testimonials et sponsors, construite sans plugin — uniquement avec les APIs natives de WordPress.
 
---
 
## 📋 Objectif
 
Tester et illustrer les fonctionnalités core du développement de thème WordPress :
 
- Enregistrement de **Custom Post Types (CPT)** natif
- **Champs personnalisés** via meta boxes (sans ACF ni plugin tiers)
- Template hierarchy & **template parts**
- **WP_Query** personnalisée
- **Theme supports** et options natives
 
---
 
## 🗂️ Structure du thème
 
```
wp-landing-theme/
├── style.css                  # En-tête du thème (obligatoire)
├── functions.php              # CPT, meta boxes, enqueue, supports
├── index.php                  # Fallback
├── front-page.php             # Template de la landing page
│
├── template-parts/
│   ├── hero.php               # Section hero
│   ├── testimonials.php       # Boucle testimonials
│   └── sponsors.php           # Boucle sponsors
│
├── inc/
│   ├── cpt.php                # Enregistrement des CPT
│   └── meta-boxes.php         # Meta boxes & save
│
└── assets/
    ├── css/style.css
    └── js/main.js
```
 
---
 
## 📦 Custom Post Types
 
Deux CPT enregistrés nativement dans `inc/cpt.php` via `register_post_type()`.
 
### `testimonial`
 
| Champ meta      | Clé            | Type   |
|-----------------|----------------|--------|
| Nom             | `_testi_name`  | text   |
| Rôle / Poste    | `_testi_role`  | text   |
| Texte du témoignage | *(contenu natif WP)* | textarea |
| Note (1–5)      | `_testi_rating`| number |
 
### `sponsor`
 
| Champ meta      | Clé               | Type   |
|-----------------|-------------------|--------|
| Nom             | `_sponsor_name`   | text   |
| URL du site     | `_sponsor_url`    | url    |
| Logo            | `_sponsor_logo_id`| image (Media Library) |
| Niveau          | `_sponsor_tier`   | select (gold / silver / bronze) |
 
---
 
## ⚙️ Fonctionnalités démontrées
 
### 1. Enregistrement des CPT
 
```php
// inc/cpt.php
function lp_register_cpts() {
    register_post_type( 'testimonial', [
        'labels'      => [ 'name' => 'Testimonials', 'singular_name' => 'Testimonial' ],
        'public'      => true,
        'has_archive' => false,
        'supports'    => [ 'title', 'editor', 'thumbnail' ],
        'menu_icon'   => 'dashicons-format-quote',
        'show_in_rest'=> true,
    ]);
 
    register_post_type( 'sponsor', [
        'labels'      => [ 'name' => 'Sponsors', 'singular_name' => 'Sponsor' ],
        'public'      => true,
        'has_archive' => false,
        'supports'    => [ 'title' ],
        'menu_icon'   => 'dashicons-star-filled',
        'show_in_rest'=> true,
    ]);
}
add_action( 'init', 'lp_register_cpts' );
```
 
### 2. Meta boxes sans plugin
 
```php
// inc/meta-boxes.php
function lp_add_meta_boxes() {
    add_meta_box(
        'testimonial_meta',
        'Informations du témoignage',
        'lp_testimonial_meta_cb',
        'testimonial',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'lp_add_meta_boxes' );
 
function lp_testimonial_meta_cb( $post ) {
    wp_nonce_field( 'lp_testimonial_meta', 'lp_testimonial_nonce' );
    $name   = get_post_meta( $post->ID, '_testi_name', true );
    $role   = get_post_meta( $post->ID, '_testi_role', true );
    $rating = get_post_meta( $post->ID, '_testi_rating', true );
    // Affichage des champs HTML...
}
 
function lp_save_testimonial_meta( $post_id ) {
    if ( ! wp_verify_nonce( $_POST['lp_testimonial_nonce'] ?? '', 'lp_testimonial_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
 
    update_post_meta( $post_id, '_testi_name',   sanitize_text_field( $_POST['testi_name']   ?? '' ) );
    update_post_meta( $post_id, '_testi_role',   sanitize_text_field( $_POST['testi_role']   ?? '' ) );
    update_post_meta( $post_id, '_testi_rating', intval( $_POST['testi_rating'] ?? 0 ) );
}
add_action( 'save_post_testimonial', 'lp_save_testimonial_meta' );
```
 
### 3. WP_Query dans les template parts
 
```php
// template-parts/testimonials.php
$query = new WP_Query([
    'post_type'      => 'testimonial',
    'posts_per_page' => 6,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
 
if ( $query->have_posts() ) :
    while ( $query->have_posts() ) : $query->the_post();
        $name   = get_post_meta( get_the_ID(), '_testi_name', true );
        $role   = get_post_meta( get_the_ID(), '_testi_role', true );
        $rating = get_post_meta( get_the_ID(), '_testi_rating', true );
        // Rendu HTML...
    endwhile;
    wp_reset_postdata();
endif;
```
 
---
 
## 🚀 Installation
 
1. Copier le dossier du thème dans `wp-content/themes/`
2. Activer le thème dans **Apparence → Thèmes**
3. Créer une page statique et la définir comme page d'accueil dans **Réglages → Lecture**
4. Ajouter des entrées via **Testimonials** et **Sponsors** dans l'admin
 
---
 
## 📌 Notes techniques
 
- Aucune dépendance externe — zéro plugin requis
- Compatible WordPress 6.x
- `show_in_rest: true` activé sur les CPT → compatible avec l'éditeur Gutenberg
- Nonces systématiquement vérifiés lors du `save_post`
- Données nettoyées via `sanitize_text_field()` et `intval()` avant stockage
- `wp_reset_postdata()` appelé après chaque `WP_Query` custom
 
---
 
## 🔑 Concepts WordPress illustrés
 
| Concept | Fichier |
|---|---|
| `register_post_type()` | `inc/cpt.php` |
| `add_meta_box()` | `inc/meta-boxes.php` |
| `save_post` hook + nonce | `inc/meta-boxes.php` |
| `get_post_meta()` | `template-parts/*.php` |
| `WP_Query` | `template-parts/*.php` |
| `get_template_part()` | `front-page.php` |
| `wp_enqueue_scripts` | `functions.php` |
| `add_theme_support()` | `functions.php` |
 
---
 
## 📄 Licence
 
MIT — libre d'utilisation pour vos projets d'apprentissage ou de prototypage.
