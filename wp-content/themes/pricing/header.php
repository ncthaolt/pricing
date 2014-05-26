<!DOCTYPE html>
<html>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php wp_title('|', true, 'right'); ?></title>
        <meta name="description" content="Hải Dóng Website">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--[if lt IE 9]>
          <script src="<?php echo get_template_directory_uri()?>/js/html5shiv.js"></script>
          <script src="<?php echo get_template_directory_uri()?>/js/respond.min.js"></script>
        <![endif]-->
        <script text="text/javascript">
            var template_link = "<?php echo get_bloginfo('template_url')?>";
            var base_url = "<?php bloginfo('url'); ?>";

        </script>
        <?php wp_head(); ?>
    </head>
    <body>