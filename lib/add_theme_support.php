<?php

/************************************************************
add support for built in features
************************************************************/
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );

// custom sizes
add_image_size( 'catalog-thumb', 180, 240, true ); // 180x240 cropped
add_image_size( 'similar-thumb', 150, 150, true); // 150x150 cropped
add_image_size( 'collection-thumb', 120, 160, true); // 120x160 cropped
add_image_size( 'size-50', 50, 50, true); // 50x50 cropped

?>