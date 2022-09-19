<?php
function carolinaspa_setup() {
    add_image_size('blog_entry', 400, 257, true);
}
add_action('after_setup_theme', 'carolinaspa_setup');

// Enqueue CSS and JS
function carolinaspa_scripts() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');
}
add_action('wp_enqueue_scripts', 'carolinaspa_scripts');

function add_fontawesome_kit() {
    wp_register_script( 'fa-kit', 'https://kit.fontawesome.com/4861f2b45b.js', array( 'jquery' ) , '5.9.0', true ); // — From an External URL
    wp_enqueue_script( 'fa-kit' );
}
add_action( 'wp_enqueue_scripts', 'add_fontawesome_kit', 100 );

// =========================================================================
// STOREFRONT REMOVE SIDEBAR - FULL WIDTH LAYOUT
// =========================================================================
function carolinaspa_remove_storefront_sidebar() {
    remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
    add_filter( 'body_class', function( $classes ) {
        return array_merge( $classes, array( 'page-template-template-fullwidth page-template-template-fullwidth-php ' ) );
    } );
}
add_action( 'get_header', 'carolinaspa_remove_storefront_sidebar' );


// Remove the homepage content and display featured image
// Remove "Fan favorite" & "On sale" sections on homepage;
function carolinaspa_homepage_content(){
    remove_action('homepage', 'storefront_homepage_content');
    remove_action('homepage', 'storefront_popular_products',50);
    remove_action('homepage', 'storefront_on_sale_products',60);
    // Remove breadcrumb
    remove_action('storefront_before_content', 'woocommerce_breadcrumb',10);

    add_action('homepage', 'carolinaspa_homepage_coupon',10);
}
add_action('init', 'carolinaspa_homepage_content');

function carolinaspa_homepage_coupon() {
    echo "<div class='main-content'>";
    the_post_thumbnail();
    echo "</div>";
}

// Display Homekit on the home page
function carolinaspa_homepage_features() {
    ?>
        <div class="homepage-features">
            <div class="container">
                <div class="columns-4">
                    <img src="<?php echo get_field('feature_1_img') ?>" alt="Feature 1 img">
                    <div class="content">
                        <div class="title">
                            <?php echo get_field('feature_1_title') ?>
                        </div>
                        <div class="text">
                            <?php echo get_field('featured_1_text') ?>
                        </div>
                    </div>
                </div>
                <div class="columns-4">
                    <img src="<?php echo get_field('feature_2_img') ?>" alt="Feature 2 img">
                    <div class="content">
                        <div class="title">
                            <?php echo get_field('feature_2_title') ?>
                        </div>
                        <div class="text">
                            <?php echo get_field('featured_2_text') ?>
                        </div>
                    </div>
                </div>
                <div class="columns-4">
                    <img src="<?php echo get_field('feature_3_img') ?>" alt="Feature 3 img">
                    <div class="content">
                        <div class="title">
                            <?php echo get_field('feature_3_title') ?>
                        </div>
                        <div class="text">
                            <?php echo get_field('featured_3_text') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
}
add_action('homepage', 'carolinaspa_homepage_features', 15);

// Display Homekit on the home page
function carolinaspa_homepage_homekits() {
    ?>
        <div class="homepage-homekits-category">
            <div class="content">
                <div class="columns-3">
                    <?php  $home_kit = get_term(17, 'product_cat', ARRAY_A); ?>
                    <h2 class="section-title"><?php echo $home_kit['name']; ?></h2>
                    <p><?php echo $home_kit['description']; ?></p>
                    <a href="<?php echo get_category_link($home_kit['term_id']) ?>">
                        All Products >>
                    </a> 
                </div>
            </div>
            <?php echo do_shortcode('[product_category category="kits" per_page="3" orderby="price" order="desc" columns="9" ]'); ?>
        </div>
    <?php
}
add_action('homepage', 'carolinaspa_homepage_homekits', 25);

// Display Spoil section on the home page
function carolinaspa_homepage_spoil() {
    ?>
      <div class="homepage-spoil-section">
        <h3 class="spoil-title">
            <?php echo get_field('spoil_text') ?>
        </h2>
            <img src="<?php echo get_field('spoil_banner') ?>" alt="Spoil Banner">
      </div>  
    <?php
}   
add_action('homepage', 'carolinaspa_homepage_spoil', 75);

// Display blog entries on home page
function carolinaspa_homepage_blog_entries() {
    $args = array(
        'post_type' => 'post',
        'post_per_page' => 3,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $entries = new WP_Query($args);
    ?>
        <div class="homepage-blog-entries">
            <h2 class="section-title">Latest Blog</h2>
            <ul>
                <?php while($entries->have_posts()): $entries->the_post(); ?>
                <li>
                    <?php the_post_thumbnail('blog_entry') ?>
                    <h1 class="blog-title"><?php the_title(); ?></h1>
                    <div class="blog-content">
                        
                        <span>By: <?php the_author(); ?> | <?php the_time(get_option('date_format')) ?>  </span>
                        
                        <p><?php
                            $content = wp_trim_words(get_the_content(), 20, '...');
                            echo $content;
                        ?></p>
                        <a href="<?php the_permalink() ?>" class="blog-link">Read more >>></a>
                    </div>
                </li>
                <?php endwhile; wp_reset_postdata(); ?>
            </ul>
        </div>
    <?php
}
add_action('homepage', 'carolinaspa_homepage_blog_entries', 80);

// Display newsletter
function carolinaspa_newsletter(){
    ?>  
        <div class="newsletter-container">
            <div class="newsletter-content">
                <div class="title">Subscribe to our newsletter</div>
                <p class="subtitle">access to our exclusive deals</p>
            </div>
            <!-- Begin Mailchimp Signup Form -->
            <div id="mc_embed_signup">
                <form action="https://gmail.us10.list-manage.com/subscribe/post?u=aefce50fecd755e17ce18250f&amp;id=504669b502&amp;f_id=00332fe2f0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                    <div class="mc-field-group">
                        <input type="email" value="" placeholder="Email" name="EMAIL" class="required email" id="mce-EMAIL" required>
                        <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
                    </div>
                        <div id="mce-responses" class="clear foot">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div>
                </form>
            </div>
        </div>
        <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';fnames[5]='BIRTHDAY';ftypes[5]='birthday';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
        <!--End mc_embed_signup-->
    <?php
}
add_action('storefront_before_footer','carolinaspa_newsletter' );

// Remove default Woocommerce footer and create a new one
function carolinaspa_footer() {
    remove_action('storefront_footer', 'storefront_credit', 20 );
    add_action('storefront_after_footer', 'carolinaspa_new_footer', 20);
}
add_action('init','carolinaspa_footer');

function carolinaspa_new_footer(){
    echo "<div class='reserved'>";
    echo "All Rights Reserved &copy; " . get_bloginfo() . " " . get_the_date('Y');
    echo "</div>";
}
