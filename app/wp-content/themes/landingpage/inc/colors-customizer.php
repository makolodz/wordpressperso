<?php

function customizer_colors( $wp_customize ) {

    // SECTION
    $wp_customize->add_section( 'colors', [
        'title'       => __( 'Colors', 'mon-theme' ),
        'description' => __( 'Choose your color settings', 'mon-theme' ),
        'priority'    => 30,
    ] );

    // Color 1 
    $wp_customize->add_setting( 'colors_1', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh', // ou 'postMessage'
    ] );

    // Color 2
    $wp_customize->add_setting( 'colors_2', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh', // ou 'postMessage'
    ] );

    // Color 3
    $wp_customize->add_setting( 'colors_3', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh', // ou 'postMessage'
    ] );

    // Color 1 control
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'colors_1_control',
        [
            'label'    => __( 'Main', 'mon-theme' ),
            'section'  => 'colors',
            'settings' => 'colors_1',
        ]
    ) );

    // Color 2 control
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'colors_2_control',
        [
            'label'    => __( 'Secondary', 'mon-theme' ),
            'section'  => 'colors',
            'settings' => 'colors_2',
        ]
    ) );

    // Color 3 control
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'colors_3_control',
        [
            'label'    => __( 'Tertiary', 'mon-theme' ),
            'section'  => 'colors',
            'settings' => 'colors_3',
        ]
    ) );
}
add_action( 'customize_register', 'customizer_colors' );