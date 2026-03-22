<?php

// ─────────────────────────────────────────────
// 1. CUSTOM POST TYPE : TESTIMONIALS
// ─────────────────────────────────────────────

function testimonials_register_cpt() {

    $labels = [
        'name'               => __( 'Testimonials', 'testimonials-theme' ),
        'singular_name'      => __( 'Testimonial', 'testimonials-theme' ),
        'add_new'            => __( 'Ajouter un témoignage', 'testimonials-theme' ),
        'add_new_item'       => __( 'Ajouter un nouveau témoignage', 'testimonials-theme' ),
        'edit_item'          => __( 'Modifier le témoignage', 'testimonials-theme' ),
        'new_item'           => __( 'Nouveau témoignage', 'testimonials-theme' ),
        'view_item'          => __( 'Voir le témoignage', 'testimonials-theme' ),
        'search_items'       => __( 'Rechercher un témoignage', 'testimonials-theme' ),
        'not_found'          => __( 'Aucun témoignage trouvé', 'testimonials-theme' ),
        'not_found_in_trash' => __( 'Aucun témoignage dans la corbeille', 'testimonials-theme' ),
        'menu_name'          => __( 'Testimonials', 'testimonials-theme' ),
        'all_items'          => __( 'Tous les témoignages', 'testimonials-theme' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => [ 'slug' => 'testimonials' ],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => [ 'title', 'thumbnail' ],
        'show_in_rest'       => true,
    ];

    register_post_type( 'testimonial', $args );
}
add_action( 'init', 'testimonials_register_cpt' );


// ─────────────────────────────────────────────
// 2. META BOX : INFOS CLIENT
// ─────────────────────────────────────────────
// 2. Meta Box
function testimonials_render_meta_box($post) {

    wp_nonce_field('testimonial_save_meta','testimonial_meta_nonce');

    $testimonial = get_post_meta(get_the_ID(), '_client_testimonial', true);
    $position = get_post_meta($post->ID,'_client_position',true);
    $company  = get_post_meta($post->ID,'_client_company',true);
    $rating   = get_post_meta($post->ID,'_client_rating',true);
    ?>

    <p>
        <label>Poste du client</label><br>
        <input type="text" name="client_position" value="<?php echo esc_attr($position); ?>" style="width:100%;">
    </p>

    <p>
        <label>Entreprise</label><br>
        <input type="text" name="client_company" value="<?php echo esc_attr($company); ?>" style="width:100%;">
    </p>

    <p>
        <label>Note</label><br>
        <select name="client_rating">
            <option value="">—</option>
            <?php for($i=1;$i<=5;$i++): ?>
                <option value="<?php echo $i; ?>" <?php selected($rating,$i); ?>><?php echo $i; ?> ⭐</option>
            <?php endfor; ?>
        </select>
    </p>

    <p>
        <label>Témoignage</label><br>
        <textarea name="client_testimonial" rows="5" style="width:100%;"><?php echo esc_textarea($testimonial); ?></textarea>
    </p>

    <?php
}

function testimonials_add_meta_boxes() {
    add_meta_box(
        'testimonial_client_meta',
        'Informations du client',
        'testimonials_render_meta_box',
        'testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes','testimonials_add_meta_boxes');



// ─────────────────────────────────────────────
// 3. SAVE META
// ─────────────────────────────────────────────

function testimonials_save_meta( $post_id ) {

    if ( ! isset( $_POST['testimonial_meta_nonce'] ) ) return;

    if ( ! wp_verify_nonce( $_POST['testimonial_meta_nonce'], 'testimonial_save_meta' ) ) return;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if ( ! current_user_can( 'edit_post', $post_id ) ) return;


    if ( isset( $_POST['client_position'] ) ) {

        update_post_meta(
            $post_id,
            '_client_position',
            sanitize_text_field( $_POST['client_position'] )
        );

    }

    if ( isset( $_POST['client_company'] ) ) {

        update_post_meta(
            $post_id,
            '_client_company',
            sanitize_text_field( $_POST['client_company'] )
        );

    }

    if ( isset( $_POST['client_rating'] ) ) {

        update_post_meta(
            $post_id,
            '_client_rating',
            intval( $_POST['client_rating'] )
        );

    }

    if ( isset( $_POST['client_testimonial'] ) ) {
        update_post_meta(
            $post_id,
            '_client_testimonial',
            sanitize_textarea_field($_POST['client_testimonial'])
        );
    }

}
add_action( 'save_post_testimonial', 'testimonials_save_meta' );


// ─────────────────────────────────────────────
// 4. COLONNES ADMIN
// ─────────────────────────────────────────────

function testimonials_add_admin_columns( $columns ) {

    $new_columns = [];

    foreach ( $columns as $key => $value ) {

        $new_columns[$key] = $value;

        if ( $key === 'title' ) {

            $new_columns['client_photo'] = 'Photo';
            $new_columns['client_company'] = 'Entreprise';
            $new_columns['client_rating'] = 'Note';

        }
    }

    return $new_columns;
}
add_filter( 'manage_testimonial_posts_columns', 'testimonials_add_admin_columns' );


function testimonials_render_admin_columns( $column, $post_id ) {

    switch ( $column ) {

        case 'client_photo':

            $thumb = get_the_post_thumbnail( $post_id, [60,60] );
            echo $thumb ? $thumb : '—';

        break;

        case 'client_company':

            echo esc_html(
                get_post_meta( $post_id, '_client_company', true )
            );

        break;

        case 'client_rating':

            $rating = get_post_meta( $post_id, '_client_rating', true );

            if ($rating) {
                echo str_repeat('⭐', $rating);
            } else {
                echo '—';
            }

        break;
    }

}
add_action(
'manage_testimonial_posts_custom_column',
'testimonials_render_admin_columns',
10,
2
);


// ─────────────────────────────────────────────
// 5. SUPPORT THEME
// ─────────────────────────────────────────────

function testimonials_theme_setup() {

    add_theme_support('post-thumbnails');

    add_theme_support('title-tag');

    add_theme_support('html5',
        ['search-form','comment-form','gallery','caption']
    );

    add_image_size('testimonial-avatar',120,120,true);

}
add_action('after_setup_theme','testimonials_theme_setup');


// ─────────────────────────────────────────────
// 6. FLUSH REWRITE RULES
// ─────────────────────────────────────────────

function testimonials_flush_rewrite() {

    testimonials_register_cpt();

    flush_rewrite_rules();

}

register_activation_hook(__FILE__,'testimonials_flush_rewrite');