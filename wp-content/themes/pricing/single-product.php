<?php
get_header();?>

<?php while ( have_posts() ) : the_post();

//Get shop
$value = get_post_meta($post->ID, '_shop_field', true);
$value = unserialize($value);
$shop = $value['shop'];
$price = $value['price'];
asort($price);

$shop_list = array();
$i=0;
foreach ($price as $key => $item) {
    if($shop[$key]!='' && $item!=''){
        $shop_list[$i]['shop'] = $shop[$key];
        if($shop[$key]!=''){
            $shop_detail = get_post($shop[$key]);
            $shop_image = get_field('logo', $shop[$key]);
            $shop_list[$i]['shop_name'] = $shop_detail->post_title;
            $shop_list[$i]['shop_logo'] = $shop_image;
        }
        else{
            $shop_list[$i]['shop_name'] = '';
            $shop_list[$i]['shop_logo'] = '';
        }
        $shop_list[$i]['price'] = $item;
        $i++;
    }
}
?>
    <header class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container-fluid text-center margin-top15">
				<a href="#" class="pull-left fg-emerald fs12 text-bold"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
				<strong>Sản phẩm so sánh</strong>
				<a href="#" class="pull-right fg-emerald fs12 text-bold">Search <span class="glyphicon glyphicon-search"></span></a>
			</div>	
		</header>
		<!--sec1-->
		<div class="container-fluid">
			<div class="text-center">
				<p><img src="<?php echo get_field('product_image');?>" alt=""></p>
                                <h4><?php the_title()?></h4>
				<p><strong class="fg-emerald fs12">Giá rẻ nhất tại <?php echo $shop_list[0]['shop_name']?>: <?php echo $shop_list[0]['price']?> VNĐ</strong></p>
			</div>
		</div>
		<!-- end sec1 -->
		
		<!--sec2-->
		<div class="border-top bd-grayLight margin-top15">
			<div class="container-fluid margin-top15">
				<div class="col-md-6 col-sm-6 col-xs-6 fs10 text-center">
                                    <?php if(function_exists('the_ratings')) { the_ratings(); } ?>
<!--					<div class="rate-me">
						<a href="#" class="fg-emerald"><span class="glyphicon glyphicon-star"></span></a>
						<a href="#" class="fg-emerald"><span class="glyphicon glyphicon-star"></span></a>
						<a href="#" class="fg-emerald"><span class="glyphicon glyphicon-star"></span></a>
						<a href="#" class="fg-grayLight"><span class="glyphicon glyphicon-star"></span></a>
						<a href="#" class="fg-grayLight"><span class="glyphicon glyphicon-star"></span></a> 
						<span class="text-bold">5 Đánh giá</span>
					</div>-->
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6 border-left bd-grayLight fs10 text-center">
					<a href="#" class="fg-gray fg-hover-emerald text-bold"><span class="glyphicon glyphicon-share fg-emerald"></span> Chia sẻ</a>
				</div>
			</div>
		</div>
		<!-- end sec2 -->
		<!--sec3-->
		<div class="bg-emerald margin-top15 padding15 fg-white text-center">
			<img src="<?php echo get_template_directory_uri() ?>/images/house.png"> Có <?php echo count($shop_list);?> website đang bán sản phẩm này
		</div>
		<!-- end sec3 -->
		
		<!--sec4-->
		<div class="row no-margin">
			<!-- item -->
                        <?php if($shop_list!=null){?>
                        <?php foreach($shop_list as $detail){?>
			<div class="item border-bottom bd-grayLight padding15 clearfix">
				<div class="media">
				  <a class="pull-left" href="#">
					<img class="media-object polaroid fix32" src="<?php echo $detail['shop_logo']?>" alt="<?php echo $detail['shop_name']?>">
				  </a>
				  <div class="media-body">
					<div class="clearfix">
						<div class="pull-left padding-top5">
							<a href="#" class="fg-gray fg-hover-emerald fs12 text-bold"><?php echo $detail['shop_name']?></a>
						</div>
						<div class="pull-right">
							<a href="#" class="fg-emerald fs20 on-right"><span class="glyphicon glyphicon-chevron-right"></span></a>
						</div>
						<div class="pull-right">
							<span class="text-bold fs12"><?php echo $detail['price']?> VNĐ<span> 
							<img src="<?php echo get_template_directory_uri() ?>/images/best.png" class="fix32">
						</div>
					</div>
				  </div>
				</div>
			</div>
                        <?php } }?>
			<!-- end item -->
		</div>
		<!-- end sec4 -->
            <?php endwhile;?>
<?php
get_footer();
?>