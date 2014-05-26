<?php

if (!isset($content_width))
    $content_width = 640;
// Permission
// Disable Admin Bar for everyone
if (!function_exists('df_disable_admin_bar')) {

    function df_disable_admin_bar() {

        // for the admin page
        remove_action('admin_footer', 'wp_admin_bar_render', 1000);
        // for the front-end
        remove_action('wp_footer', 'wp_admin_bar_render', 1000);

        // css override for the admin page
        function remove_admin_bar_style_backend() {
            echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
        }

        add_filter('admin_head', 'remove_admin_bar_style_backend');

        // css override for the frontend
        function remove_admin_bar_style_frontend() {
            echo '<style type="text/css" media="screen">
			html { margin-top: 0px !important; }
			* html body { margin-top: 0px !important; }
			</style>';
        }

        add_filter('wp_head', 'remove_admin_bar_style_frontend', 99);
    }

}
add_action('init', 'df_disable_admin_bar');

/**
 * Setup theme
 */
function pricing_setup() {
    //This them uses wp_nav_menu() in one location
    register_nav_menu('primary', __('Navigation Menu', 'pricing'));
    register_nav_menu('rep-primary', __('Representative Menu', 'pricing'));
    register_nav_menu('super-primary', __('Super Admin Menu', 'pricing'));

    /*
     * This theme uses a custom image size for featured images, displayed on
     * "standard" posts and pages.
     */
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(604, 270, true);
}

add_action('after_setup_theme', 'pricing_setup');

function pricing_scripts_styles() {
    //main theme stylesheet
    wp_enqueue_style('pricing-theme-base', get_template_directory_uri() . '/css/bt.css', array());
    wp_enqueue_style('pricing-theme-temp', get_template_directory_uri() . '/css/template.css', array());

    //Script
    wp_deregister_script('jquery'); // Deregister WordPress jQuery
    wp_register_script('jquery', get_template_directory_uri() . '/js/jquery.js', array(), '1.11.0');
    wp_enqueue_script('jquery'); // Enqueue it!

    wp_deregister_script('bt-script');
    wp_register_script('bt-script', get_template_directory_uri() . '/js/bt.js', array());
    wp_enqueue_script('bt-script');
}

add_action('wp_enqueue_scripts', 'pricing_scripts_styles');

/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function pricing_wp_title($title, $sep) {
    global $paged, $page;

    if (is_feed())
        return $title;

    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() ))
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2)
        $title = "$title $sep " . sprintf(__('Page %s', 'pricing'), max($paged, $page));

    return $title;
}

add_filter('wp_title', 'pricing_wp_title', 10, 2);

function pricing_header_nav() {
    wp_nav_menu(
            array(
                'theme_location' => 'primary',
                'menu' => 'primary',
                'container' => 'false',
                'container_class' => 'top-menu menu-homepage',
                'container_id' => '',
                'menu_id' => '',
                'echo' => true,
                'fallback_cb' => 'wp_page_menu',
                'link_before' => '',
                'link_after' => '',
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth' => 0,
                'walker' => new sn_walker_nav_menu
            )
    );
}

function pricing_rep_header_nav() {
    wp_nav_menu(
            array(
                'theme_location' => 'rep-primary',
                'menu' => 'rep-primary',
                'container' => 'false',
                'container_class' => 'top-menu menu-homepage',
                'container_id' => '',
                'menu_id' => '',
                'echo' => true,
                'fallback_cb' => 'wp_page_menu',
                'link_before' => '',
                'link_after' => '',
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth' => 0,
                'walker' => new sn_walker_nav_menu
            )
    );
}

function pricing_admin_header_nav() {
    wp_nav_menu(
            array(
                'theme_location' => 'super-primary',
                'menu' => 'super-primary',
                'container' => 'false',
                'container_class' => 'top-menu menu-homepage',
                'container_id' => '',
                'menu_id' => '',
                'echo' => true,
                'fallback_cb' => 'wp_page_menu',
                'link_before' => '',
                'link_after' => '',
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth' => 0,
                'walker' => new sn_walker_nav_menu
            )
    );
}

class sn_walker_nav_menu extends Walker_Nav_Menu {

    // Set the properties of the element which give the ID of the current item and its parent 
    // Displays start of a level. E.g '<ul>'  
    // @see Walker::start_lvl()  
    function start_lvl(&$output, $depth = 0, $args = array()) {
        // depth dependent classes
        $indent = ( $depth > 0 ? str_repeat("\t", $depth) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array(
            'sub-menu',
            ( $display_depth % 2 ? 'menu-odd' : 'menu-even' ),
            ( $display_depth >= 2 ? 'sub-sub-menu' : '' ),
            'menu-depth-' . $display_depth
        );
        $class_names = implode(' ', $classes);

        // build html
        $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
    }

    // Displays end of a level. E.g '</ul>'  
    // @see Walker::end_lvl()  
    function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "</ul>\n";
    }

}

/**
 * Create product post type
 */
function product_init() {
    $labels = array(
        'name' => 'Products',
        'menu_name' => 'Products',
        'all_items' => __('All Products'),
        'update_item' => __('Update Product'),
        'add_new_item' => __('Add New Product'),
        'new_item_name' => __('New Product Name'),
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor'),
        'rewrite' => array(
            'slug' => 'product',
            'with_front' => FALSE,
        ),
//        'taxonomies' => array('post_tag') // this is IMPORTANT
    );
    register_post_type('product', $args);
    flush_rewrite_rules(false);
}

/**
 * Create shop post type
 */
function shop_init() {
    $labels = array(
        'name' => 'Shops',
        'menu_name' => 'Shops',
        'all_items' => __('All Shops'),
        'update_item' => __('Update Shop'),
        'add_new_item' => __('Add New Shop'),
        'new_item_name' => __('New Shop Name'),
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor'),
        'rewrite' => array(
            'slug' => 'shop',
            'with_front' => FALSE,
        ),
//        'taxonomies' => array('post_tag') // this is IMPORTANT
    );
    register_post_type('shop', $args);
    flush_rewrite_rules(false);
}

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function shop_add_meta_box() {

    $screens = array('product');

    foreach ($screens as $screen) {

        add_meta_box(
                'shop_sectionid', __('Shop', 'shop_textdomain'), 'shop_meta_box_callback', $screen
        );
    }
}

add_action('add_meta_boxes', 'shop_add_meta_box');

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function shop_meta_box_callback($post) {

    // Add an nonce field so we can check for it later.
    wp_nonce_field('shop_meta_box', 'shop_meta_box_nonce');

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta($post->ID, '_shop_field', true);
    $value = unserialize($value);
    $shop_args = array(
        'posts_per_page'  => -1,
        'order'           => 'ASC',
        'post_type'       => 'shop',
        'post_status'     => 'publish',
        'suppress_filters' => true );

    $shops = get_posts($shop_args);
    if($shops!=null){
    ?>
        <table id="shop_field">
            <thead>
                <tr>
                    <th>
                        Shop
                    </th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if($value['shop']!=null && $value['price']!=null){?>
                <?php foreach($value['shop'] as $i=>$value_item){?>
                    <tr>
                        <td>
                            <?php
                            echo '<select name="shop_new_field[shop][]" style="width:200px">';
                                echo '<option value="">--Select--</option>';
                                foreach($shops as $shop){
                                    $select = ($value['shop'][$i]==$shop->ID)?"selected":"";
                                    echo '<option value="'.$shop->ID.'" '.$select.'>'.$shop->post_title.'</option>';
                                }
                            echo '</select>';
                            ?>
                        </td>
                        <td>
                            <?php echo '<input type="text" name="shop_new_field[price][]" value="'.$value['price'][$i].'" size="25" />';?> VNĐ
                        </td>
                    </tr>
                <?php }?>
                <?php } else{?>
                <tr>
                    <td>
                        <?php
                        echo '<select name="shop_new_field[shop][]" style="width:200px">';
                            echo '<option value="">--Select--</option>';
                            foreach($shops as $shop){
                                echo '<option value="'.$shop->ID.'">'.$shop->post_title.'</option>';
                            }
                        echo '</select>';
                        ?>
                    </td>
                    <td>
                        <?php echo '<input type="text" name="shop_new_field[price][]" value="" size="25" />';?> VNĐ
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
        <br/>
        <img src="<?php echo get_template_directory_uri()?>/images/add-2-icon.png" alt="Add more" title="Add more" width="30px" id="add_more"/>
        <?php
        
    }
    
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function shop_save_meta_box_data($post_id) {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if (!isset($_POST['shop_meta_box_nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['shop_meta_box_nonce'], 'shop_meta_box')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {

        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    /* OK, its safe for us to save the data now. */

    // Make sure that it is set.
    if (!isset($_POST['shop_new_field'])) {
        return;
    }
    // Sanitize user input.
    $my_data = serialize($_POST['shop_new_field']);

    // Update the meta field in the database.
    update_post_meta($post_id, '_shop_field', $my_data);
}
function admin_register_head() {
    $url = get_bloginfo('template_directory') . '/css/admin.css';
    $jquery = get_bloginfo('template_directory') . '/js/jquery.js';
    $js = get_bloginfo('template_directory') . '/js/admin_js.js';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
    echo "<script src='$jquery'></script>\n";
    echo "<script src='$js'></script>\n";
    echo '<script text="text/javascript"> var template_link = "'.get_bloginfo('template_url').'";</script>';
}

add_action('admin_head', 'admin_register_head');
add_action('save_post', 'shop_save_meta_box_data');

add_action('init', 'product_init');
add_action('init', 'shop_init');
