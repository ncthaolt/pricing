<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
global $wpdb;

$shop_args = array(
    'posts_per_page'  => -1,
    'order'           => 'ASC',
    'post_type'       => 'shop',
    'post_status'     => 'publish',
    'suppress_filters' => true );

$shops = get_posts($shop_args);
if($shops!=null){
    ?>
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
<?php
}