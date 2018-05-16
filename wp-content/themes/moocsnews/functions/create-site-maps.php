<?php

add_action( "save_post", "itvndocorg_create_sitemap" );   
add_action( "clean_term_cache", "itvndocorg_create_sitemap_categories_tags" );   
function itvndocorg_create_sitemap() {
    $postsForSitemap = get_posts( array(
        'numberposts' => -1,
        'orderby'     => 'modified',
        'post_type'   => array( 'course' ),
        'order'       => 'DESC'
    ) );
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap .= "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";    
    foreach( $postsForSitemap as $post ) {
        setup_postdata( $post );   
        $postdate = explode( " ", $post->post_modified );   
        $sitemap .= "\t" . '<url>' . "\n" .
            "\t\t" . '<loc>' . get_permalink( $post->ID ) . '</loc>' .
            "\n\t\t" . '<lastmod>' . $postdate[0] . '</lastmod>' .
            "\n\t\t" . '<changefreq>weekly</changefreq>' .
            "\n\t" . '</url>' . "\n";
    }     
    $sitemap .= '</urlset>';     
    $fp = fopen( ABSPATH . "sitemap.xml", 'w' );
    fwrite( $fp, $sitemap );
    fclose( $fp );
    itvndocorg_create_sitemap_categories();
    itvndocorg_create_sitemap_tags();
}

/* function to create/update sitemap-categories.xml/sitemap-tags.xml file in root directory of site  */ 
function itvndocorg_create_sitemap_categories_tags(){
	if($_POST['taxonomy'] === 'category'){
		itvndocorg_create_sitemap_categories();
	}

	if($_POST['taxonomy'] === 'post_tag'){
		itvndocorg_create_sitemap_tags();
	}
	
}

/* function to create/update sitemap-categories.xml file in root directory of site  */  
function itvndocorg_create_sitemap_categories(){
	$categories = get_categories(); 

    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap .= "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";    
    foreach( $categories as $item ) { 
        $sitemap .= "\t" . '<url>' . "\n" .
            "\t\t" . '<loc>' . get_category_link( $item ) . '</loc>' .
            "\n\t\t" . '<lastmod>' . date('Y-m-d') . '</lastmod>' .
            "\n\t\t" . '<changefreq>weekly</changefreq>' .
            "\n\t" . '</url>' . "\n";
    }     
    $sitemap .= '</urlset>'; 

    $fp = fopen( ABSPATH . "sitemap-categories.xml", 'w' );
    fwrite( $fp, $sitemap );
    fclose( $fp );
}

/* function to create/update sitemap-tags.xml file in root directory of site  */ 
function itvndocorg_create_sitemap_tags(){
	$tags = get_tags();

    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap .= "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";    
    foreach( $tags as $item ) { 
        $sitemap .= "\t" . '<url>' . "\n" .
            "\t\t" . '<loc>' . get_tag_link($item->term_id) . '</loc>' .
            "\n\t\t" . '<lastmod>' . date('Y-m-d') . '</lastmod>' .
            "\n\t\t" . '<changefreq>weekly</changefreq>' .
            "\n\t" . '</url>' . "\n";
    }     
    $sitemap .= '</urlset>'; 

    $fp = fopen( ABSPATH . "sitemap-tags.xml", 'w' );
    fwrite( $fp, $sitemap );
    fclose( $fp );
}