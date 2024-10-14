<?php

if ( ! class_exists( 'Bam_Gutenberg' ) ) {

    /**
     * Gutenberg related functions.
     */
    class Bam_Gutenberg {

        public function __construct() {
            add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_fonts' ) );
            add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_styles' ) );
        }

        /**
         * @return array.
         */
        public function elements() {
            return array(
                'body'  => array(
                    'title'     => esc_html__( 'Body', 'bam' ),
                    'target'    => 'html .editor-styles-wrapper',
                    'defaults'  => array(
                        'font-family'   => 'Source Sans Pro',
                        'font-size'     => array(
                            'desktop'   => '18px'
                        ),
                        'line-height'   => 1.5,
                        'color'         => '#404040'
                    )
                ),

                'headings'  => array(
                    'title'     => esc_html__( 'All Headings', 'bam' ),
                    'target'    => 'html .editor-styles-wrapper h1, html .editor-styles-wrapper h2, html .editor-styles-wrapper h3, html .editor-styles-wrapper h4, html .editor-styles-wrapper h5, html .editor-styles-wrapper h6, .editor-styles-wrapper .editor-post-title__input',
                    'defaults'  => array(
                        'font-family'   => 'Roboto Condensed',
                        'line-height'   => 1.3,
                        'color'         => '#000000'
                    )
                ),

                'h1'  => array(
                    'title'     => esc_html__( 'Heading 1 (h1)', 'bam' ),
                    'target'    => 'html .editor-styles-wrapper h1, .editor-styles-wrapper .editor-post-title__input',
                    'defaults'  => array(
                        'font-size'     => array(
                            'desktop'   => '36px'
                        ),
                        'line-height'   => 1.3,
                        'color'         => '#000000'
                    )
                ),

                'h2'  => array(
                    'title'     => esc_html__( 'Heading 2 (h2)', 'bam' ),
                    'target'    => 'html .editor-styles-wrapper h2',
                    'defaults'  => array(
                        'font-size'     => array(
                            'desktop'   => '32px'
                        ),
                        'line-height'   => 1.3,
                        'color'         => '#000000'
                    )
                ),

                'h3'  => array(
                    'title'     => esc_html__( 'Heading 3 (h3)', 'bam' ),
                    'target'    => 'html .editor-styles-wrapper h3',
                    'defaults'  => array(
                        'font-size'     => array(
                            'desktop'   => '28px'
                        ),
                        'line-height'   => 1.3,
                        'color'         => '#000000'
                    )
                ),

                'h4'  => array(
                    'title'     => esc_html__( 'Heading 4 (h4)', 'bam' ),
                    'target'    => 'html .editor-styles-wrapper h4',
                    'defaults'  => array(
                        'font-size'     => array(
                            'desktop'   => '24px'
                        ),
                        'line-height'   => 1.3,
                        'color'         => '#000000'
                    )
                ),

                'post_entry_title'  => array(
                    'title'     => esc_html__( 'Single Post Entry Title', 'bam' ),
                    'target'    => '.editor-styles-wrapper .editor-post-title__input',
                    'defaults'  => array(
                        'font-size'     => array(
                            'desktop'   => '36px'
                        ),
                        'line-height'   => 1.3,
                        'color'         => '#000000'
                    )
                ),

                'single_post_body'  => array(
                    'title'     => esc_html__( 'Single Post Content', 'bam' ),
                    'target'    => 'html .editor-styles-wrapper',
                    'defaults'  => array(
                        'font-size'     => array(
                            'desktop'   => '18px'
                        ),
                        'line-height'   => 1.7,
                        'color'         => '#404040'
                    )
                ),

                'page_entry_title'  => array(
                    'title'     => esc_html__( 'Page Title', 'bam' ),
                    'target'    => '.post-type-page .editor-styles-wrapper .editor-post-title__input',
                    'defaults'  => array(
                        'font-size'     => array(
                            'desktop'   => '36px'
                        ),
                        'line-height'   => 1.3,
                        'color'         => '#000000'
                    )
                )
            );
        }

        /**
         * Add custom inline style for editor.
         *
         * @return string
         */
        private function block_editor_inline_style() {

            $css = '';
            
            $elements = $this->elements();

            foreach ( $elements as $element => $values ) {
                
                /**
                 * Note: Removed 'color' property because if it is white it cannot be readable on Gutenberg editor since the background is white.
                 * Do not add that without applying the background color property to 'html .editer-styles-wrapper'.
                 */
                $properties = array(
                    'font-family',
                    'font-weight',
                    'font-style',
                    'text-transform',
                    'font-size',
                    'line-height'
                );
    
                $common_css = '';
                $tablet_css = '';
                $mobile_css = '';

                foreach( $properties as $property ) {

                    $setting = str_replace( '-', '_', $property );

                    // font size css properties and values.
                    if ( 'font-size' == $property ) {
                        
                        // Get default values for each device.
                        $default_desktop        = isset( $values['defaults'][$property]['desktop'] ) ? $values['defaults'][$property]['desktop'] : '';
                        $default_tablet         = isset( $values['defaults'][$property]['tablet'] ) ? $values['defaults'][$property]['tablet'] : '';
                        $default_mobile         = isset( $values['defaults'][$property]['mobile'] ) ? $values['defaults'][$property]['mobile'] : '';

                        // Theme mods for each setting.
                        $theme_setting_desktop          = get_theme_mod( 'bam_'. $element .'_desktop_'. $setting, $default_desktop );
                        $theme_setting_tablet           = get_theme_mod( 'bam_'. $element .'_tablet_'. $setting, $default_tablet );
                        $theme_setting_mobile           = get_theme_mod( 'bam_'. $element .'_mobile_'. $setting, $default_mobile );

                        // CSS properties and values for desktop.
                        if ( ! empty( $theme_setting_desktop ) && $default_desktop != $theme_setting_desktop ) {
                            $common_css .= $property .':'. esc_attr( $theme_setting_desktop ) .';';
                        } 

                        // CSS properties and values for tablet.
                        if ( ! empty( $theme_setting_tablet ) && $default_tablet != $theme_setting_tablet ) {
                            $tablet_css .= $property .':'. esc_attr( $theme_setting_tablet ) .';';
                        } 
                        
                        // CSS properties and values for mobile.
                        if ( ! empty( $theme_setting_mobile ) && $default_mobile != $theme_setting_mobile ) {
                            $mobile_css .= $property .':'. esc_attr( $theme_setting_mobile ) .';';
                        }

                    } elseif ( $property == 'font-family' ) {

                        // Default values defined in elements array.
                        $default = isset( $values['defaults'][$property] ) ? $values['defaults'][$property] : '';

                        // Theme mod.
                        $theme_setting = get_theme_mod( 'bam_'. $element .'_'. $setting, $default );

                        // CSS properties and values.
                        if ( ! empty( $theme_setting ) && $default != $theme_setting ) {
                            if ( $theme_setting === 'system-stack' ) {
                                // System Fonts Stack.
                                $system_stack = apply_filters( 'bam_typography_system_stack', '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif' );
                                
                                $common_css .= $property .':'.  $system_stack .';';
                            } else {
                                $common_css .= $property .':'. esc_attr( $theme_setting ).';';
                            }
                        }

                    } else {
                        // Default values defined in elements array.
                        $default = isset( $values['defaults'][$property] ) ? $values['defaults'][$property] : '';

                        // Theme mod.
                        $theme_setting = get_theme_mod( 'bam_'. $element .'_'. $setting, $default );

                        // CSS properties and values.
                        if ( ! empty( $theme_setting ) && $default != $theme_setting ) {
                            $common_css .= $property .':'. esc_attr( $theme_setting ).';';
                        }
                    }

                }

                // Targeted selectors
                $selectors = $values['target'];

                // Common CSS appiled to all devices.
                if ( ! empty( $common_css ) ) {
                    $css .= $selectors .'{'. $common_css .'}';
                }

                // CSS for tablet devices.
                if ( ! empty( $tablet_css ) ) {
                    $css .= '@media(max-width: 768px){'. $selectors .'{'. $tablet_css .'}}';
                }

                // CSS for mobile devices.
                if ( ! empty( $mobile_css ) ) {
                    $css .= '@media(max-width: 480px){'. $selectors .'{'. $mobile_css .'}}';
                }

            }

            return $css;

        }

        /**
         * Enqueue block editor fonts.
         */
        public function block_editor_fonts() {
            
            $body_font              = get_theme_mod( 'bam_body_font_family', 'Source Sans Pro' );
            $headings_font          = get_theme_mod( 'bam_headings_font_family', 'Roboto Condensed' );
            $h1_font                = get_theme_mod( 'bam_h1_font_family', '' );
            $h2_font                = get_theme_mod( 'bam_h2_font_family', '' );
            $h3_font                = get_theme_mod( 'bam_h3_font_family', '' );
            $h4_font                = get_theme_mod( 'bam_h4_font_family', '' );
            $single_post_body_font  = get_theme_mod( 'bam_single_post_body_font_family', '' );

            $fonts = array(
                $body_font, $headings_font, $h1_font, $h2_font, $h3_font, $h4_font, $single_post_body_font
            );

            $fonts_uri = bam_get_google_font_uri( $fonts );

            // Load Google Fonts
            wp_enqueue_style( 'bam-google-fonts', $fonts_uri, array(), null );

        }

        public function block_editor_styles() {
            
            $dir_uri = BAM_DIR_URI;

            // Block styles.
            wp_enqueue_style( 'bam-block-editor-style', $dir_uri . '/assets/css/editor-blocks.css', array(), '1.0.0' );

            // Get custom css.
            $css = $this->block_editor_inline_style();

            // Add custom css to the editor.
            wp_add_inline_style( 'bam-block-editor-style', $css );

        }


    }

}

return new Bam_Gutenberg();