<?php 

// ─────────────────────────────────────────────
// 2. CUSTOM POST TYPE : SPONSORS
// ─────────────────────────────────────────────

function sponsors_register_cpt() {
    $labels = [
        'name'                  => __( 'Sponsors', 'sponsors-theme' ),
        'singular_name'         => __( 'Sponsor', 'sponsors-theme' ),
        'add_new'               => __( 'Ajouter un sponsor', 'sponsors-theme' ),
        'add_new_item'          => __( 'Ajouter un nouveau sponsor', 'sponsors-theme' ),
        'edit_item'             => __( 'Modifier le sponsor', 'sponsors-theme' ),
        'new_item'              => __( 'Nouveau sponsor', 'sponsors-theme' ),
        'view_item'             => __( 'Voir le sponsor', 'sponsors-theme' ),
        'search_items'          => __( 'Rechercher un sponsor', 'sponsors-theme' ),
        'not_found'             => __( 'Aucun sponsor trouvé', 'sponsors-theme' ),
        'not_found_in_trash'    => __( 'Aucun sponsor dans la corbeille', 'sponsors-theme' ),
        'menu_name'             => __( 'Sponsors', 'sponsors-theme' ),
        'all_items'             => __( 'Tous les sponsors', 'sponsors-theme' ),
    ];
 
    $args = [
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => [ 'slug' => 'sponsors' ],
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-star-filled',
        'supports'            => [ 'title', 'thumbnail' ], // title + image (featured image)
        'show_in_rest'        => true,
    ];
 
    register_post_type( 'sponsor', $args );
}
add_action( 'init', 'sponsors_register_cpt' );
 
 
// ─────────────────────────────────────────────
// 3. META BOX : CHAMP LIEN
// ─────────────────────────────────────────────
function sponsors_add_meta_boxes() {
    add_meta_box(
        'sponsor_link_meta',
        __( 'Lien du sponsor', 'sponsors-theme' ),
        'sponsors_render_link_meta_box',
        'sponsor',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'sponsors_add_meta_boxes' );
 
function sponsors_render_link_meta_box( $post ) {
    // Nonce de sécurité
    wp_nonce_field( 'sponsor_save_link', 'sponsor_link_nonce' );
 
    $sponsor_url = get_post_meta( $post->ID, '_sponsor_url', true );
    ?>
    <table class="form-table" style="width:100%;">
        <tr>
            <th scope="row">
                <label for="sponsor_url"><?php _e( 'URL du site', 'sponsors-theme' ); ?></label>
            </th>
            <td>
                <input
                    type="url"
                    id="sponsor_url"
                    name="sponsor_url"
                    value="<?php echo esc_attr( $sponsor_url ); ?>"
                    placeholder="https://exemple.com"
                    style="width: 100%; max-width: 600px;"
                    class="regular-text"
                />
                <p class="description"><?php _e( 'Lien externe vers le site du sponsor (avec https://)', 'sponsors-theme' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}
 
function sponsors_save_meta( $post_id ) {
    // Vérifications de sécurité
    if ( ! isset( $_POST['sponsor_link_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['sponsor_link_nonce'], 'sponsor_save_link' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
 
    // Sauvegarde du champ URL
    if ( isset( $_POST['sponsor_url'] ) ) {
        $url = esc_url_raw( trim( $_POST['sponsor_url'] ) );
        update_post_meta( $post_id, '_sponsor_url', $url );
    }
}
add_action( 'save_post_sponsor', 'sponsors_save_meta' );
 
 
// ─────────────────────────────────────────────
// 4. COLONNE PERSONNALISÉE DANS L'ADMIN
// ─────────────────────────────────────────────
function sponsors_add_admin_columns( $columns ) {
    $new_columns = [];
    foreach ( $columns as $key => $value ) {
        $new_columns[ $key ] = $value;
        if ( $key === 'title' ) {
            $new_columns['sponsor_image'] = __( 'Logo', 'sponsors-theme' );
            $new_columns['sponsor_url']   = __( 'Lien', 'sponsors-theme' );
        }
    }
    return $new_columns;
}
add_filter( 'manage_sponsor_posts_columns', 'sponsors_add_admin_columns' );
 
function sponsors_render_admin_columns( $column, $post_id ) {
    switch ( $column ) {
        case 'sponsor_image':
            $thumb = get_the_post_thumbnail( $post_id, [ 60, 60 ] );
            echo $thumb ? $thumb : '—';
            break;
 
        case 'sponsor_url':
            $url = get_post_meta( $post_id, '_sponsor_url', true );
            if ( $url ) {
                printf(
                    '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
                    esc_url( $url ),
                    esc_html( $url )
                );
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_sponsor_posts_custom_column', 'sponsors_render_admin_columns', 10, 2 );

// ─────────────────────────────────────────────
// 6. SUPPORT THÈME
// ─────────────────────────────────────────────
function sponsors_theme_setup() {
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );
    add_image_size( 'sponsor-logo', 300, 150, false );
}
add_action( 'after_setup_theme', 'sponsors_theme_setup' );

// ─────────────────────────────────────────────
// 7. FLUSH REWRITE RULES (à l'activation)
// ─────────────────────────────────────────────
function sponsors_flush_rewrite() {
    sponsors_register_cpt();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sponsors_flush_rewrite' );
