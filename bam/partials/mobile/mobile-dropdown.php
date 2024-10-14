<div class="mobile-dropdown">
    <nav class="mobile-navigation">
        <?php
            if ( has_nav_menu( 'menu-1' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'menu-1',
                    'menu_id'        => 'primary-menu-mobile',
                    'show_toggles'   => true
                ) );
            } else {
                wp_page_menu( array(
                    'title_li'      => '',
                    'show_toggles'  => true,
                    'walker'        => new Bam_Walker_Page()
                ) );
            }
        ?>
    </nav>
</div>