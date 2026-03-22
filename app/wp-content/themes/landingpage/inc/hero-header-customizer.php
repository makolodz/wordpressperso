<?php 


function customizer_hero_header($wp_customize) {
    // SECTION
    $wp_customize->add_section( 'mon_theme_hero_header', [
        'title'       => __( 'Hero-header', 'mon-theme' ),
        'description' => __( 'Customize your Hero Header', 'mon-theme' ),
        'priority'    => 40,
    ] );

    // HERO TITLE SETTING
    $wp_customize->add_setting('hero_title', [
        'default'           => __('A lifelong pomodoro', 'mon-theme'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    // HERO TITLE CONTROL
    $wp_customize->add_control('hero_title', [
        'label'    => __('Your main value ', 'mon-theme'),
        'section'  => 'mon_theme_hero_header',
        'type'     => 'text',
    ]);

    // HERO TAGLINE SETTING
    $wp_customize->add_setting('hero_tagline', [
        'default'           => __('Our pomodoro never sleeps, never stop, and never stop working.', 'mon-theme'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    // HERO TAGLINE CONTROL
    $wp_customize->add_control('hero_tagline', [
        'label'    => __('Details', 'mon-theme'),
        'section'  => 'mon_theme_hero_header',
        'type'     => 'text',
    ]);

    // HERO CTATEXT SETTING
    $wp_customize->add_setting('hero_ctatext', [
        'default'           => __('Get Started', 'mon-theme'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    // HERO CTATEXT CONTROL
    $wp_customize->add_control('hero_ctatext', [
        'label'    => __('Your CTA ', 'mon-theme'),
        'section'  => 'mon_theme_hero_header',
        'type'     => 'text',
    ]);

    // HERO LINK SETTING
    $wp_customize->add_setting('hero_ctalink', [
        'default'           => __('nothing', 'mon-theme'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    // HERO LINK CONTROL
    $wp_customize->add_control('hero_ctalink', [
        'label'    => __('Your CTA link ', 'mon-theme'),
        'section'  => 'mon_theme_hero_header',
        'type'     => 'text',
    ]);

    // HERO MOCKUP IMAGE
    $wp_customize->add_setting('hero_mockup_image');

    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'hero_mockup_image', [
        'label'    => __('Hero Mockup Image', 'mon-theme'),
        'section'  => 'mon_theme_hero_header',
        'settings' => 'hero_mockup_image',
    ]) );

}

add_action( 'customize_register', 'customizer_hero_header' ); 