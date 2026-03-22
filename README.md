# 🎯 WP Landing Theme
 
Thème WordPress minimaliste pour tester les fonctionnalités de wordpress comme **Headless CMS**.
Une landing page simple avec testimonials et sponsors, construite sans plugin — uniquement avec les APIs natives de WordPress.
 
---


![Capture d'écran du site](/Pomodoro.png)

 
## 📋 Objectif
 
Tester et illustrer les fonctionnalités core du développement de thème WordPress :
 
- Enregistrement de **Custom Post Types (CPT)** natif
- **Champs personnalisés** via meta boxes (sans ACF ni plugin tiers)
- **WP_Query** personnalisée
- **Theme supports** et options natives
 
---
 
## 🗂️ Structure du thème
 
```
wp-landing-theme/
├── style.css                  # En-tête du thème (obligatoire)
├── functions.php              # CPT, meta boxes, enqueue, supports
├── index.php                  # Fallback (une seule page sur le thème)
│
├── template-parts/
│   └── hero.php               # Section hero
│
└── inc/
    └── CPT et CF              # Meta boxes & save

```
  
 
## 🚀 Installation
 
1. Copier le dossier du thème dans `wp-content/themes/`
2. Activer le thème dans **Apparence → Thèmes**
3. Personnaliser le hero header dans Theme -> customizer
4. Ajouter des entrées via **Testimonials** et **Sponsors** dans l'admin
 
---
 
## 📌 Notes techniques
 
- Aucune dépendance externe — zéro plugin requis
- Compatible WordPress 6.x
