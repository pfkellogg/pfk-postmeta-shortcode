<?php
/*
  Plugin Name: PFK Smart Post Meta/ACF Shortcode
  Description: Provides Smart Shortcodes To Get Post Meta or ACFs For Course And Quiz Certificates or For ßPages or Posts.
  Version: 1.0
  Author: Patrick F. Kellogg
*/

// Usage: [pfk_postmeta label_no="your no message or empty string: " label_yes="your yes message or empty string: " key="ceu"]
// Usage: post_id can be set in the shortcode as an option if shortcode is not on a certificate.
// [pfk_postmeta label_no="your no message or empty string: " label_yes="your yes message or empty string: " key="ceu" post_id="221"]

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'pfk_postmeta', 'pfk_postmeta' );

function pfk_postmeta( $atts ) {
    $array_params = array(
        'label_no' => '',
        'label_yes'  => '',
        'key'  => '',
        'post_id'  => ''
       );

    extract( shortcode_atts( $array_params , $atts ) );

    if ( isset( $atts[ 'label_no' ] ) ) {
        $label_no = $atts[ 'label_no' ];
    }

    if ( empty( $label_no ) ) {
        $label_no = '';
    }

    if ( isset( $atts[ 'label_yes' ] ) ) {
        $label_yes = $atts[ 'label_yes' ];
    }

    if ( empty( $label_yes ) ) {
        $label_yes = '';
    }

    if ( isset( $atts[ 'key' ] ) ) {
        $key = $atts[ 'key' ];
    }

    if ( empty( $key) ) {
        $key = '';
        return 'Key cannot be empty. Example shortcode for certificate is: [pfk_postmeta label_no="your no message or leave blank: " label_yes="your yes message or leave blank: " key="ceu"]. Example shortcode for non certificate (a regular page or post) is: [pfk_postmeta label_no="your no message or leave blank: " label_yes="your yes message or leave blank: " key="ceu" post_id="238"].  ';
    }

    if ( isset( $atts[ 'post_id' ] ) ) {
        $post_id = $atts[ 'post_id' ];
    }

    if ( ! empty( $key ) && ( ! empty ( $post_id ) ) ) {
        // If a $key value was entered and is not empty.
        // Get the postmeta using the passed in $key value for the post_id.
        $post_meta = get_post_meta( $post_id, $key, true );

    } elseif ( ! empty( $key ) && ( empty ( $post_id ) ) ) {
        // If a $key value was entered and there is no post_id.
        // It must be a course or quiz certificate.
        $post_id = ( empty( $post_id ) && isset( $_REQUEST[ 'course_id' ] ) )? $_REQUEST[ 'course_id' ]:'';

        if ( ! empty( $post_id ) ) {
            // Get the postmeta using the passed in $key value for the post_id.
            $post_meta = get_post_meta( $post_id, $key, true );

        } else {
            // Post_id is empty. Check for a Quiz.
            $post_id = ( isset( $_REQUEST[ 'quiz' ] ) )? $_REQUEST[ 'quiz' ]:'';
            if ( ! empty( $post_id ) ) {
                $post_meta = get_post_meta( $post_id, $key, true );
            }

        }

    } else {

        return 'No post meta found, check your key.';
    }

    if ( ! empty( $post_meta ) || (int)$post_meta === 0 ) {
        return $label_yes . ' ' . $post_meta;
    } else {
        return $label_no;
    }
}