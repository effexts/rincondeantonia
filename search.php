<?php get_header(); ?>

<?php get_template_part('template','header'); ?>

<div id="blog">
	
	<div class="wrap">
	
		<div class="blog_title">
			<h1><?php printf(__('Search Results<span>/ %s</span>','ocart'), htmlentities($s,ENT_QUOTES,get_bloginfo('charset'))); ?></h1>
			<a href="<?php echo home_url(); ?>/" class="blog_store"><?php _e('Back to Store','ocart'); ?></a>
		</div>
		
		<?php if ( have_posts() ) : ?>
		
		<div class="blog_wrap">
			<div class="blog_content">
			
				<?php if ( have_posts() ) : ?>
				<div class="postlist">
				<?php while ( have_posts() ) : the_post(); ?>
					
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
						<div class="post-thumbnail"><?php ocart_thumb(654, 234); ?></div>
						
						<div class="post-pad">
						<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
						
						<div class="post-meta">
							<?php $category = get_the_category(); $category = array_reverse($category); ?>
							<?php printf(__('By <span>%s</span> on %s with','ocart'), get_the_author(), get_the_time('F j, Y') ); ?> <?php comments_popup_link( __('0 Comments','ocart'), __('1 Comment','ocart'), __('% Comments','ocart') ); ?>
						</div>
						
						<div class="post-content">
							<?php ocart_the_content(60); ?>
							<p><a href="<?php the_permalink(); ?>" class="readmore"><?php _e('Read More','ocart'); ?></a></p>
						</div>
						</div>
							
					</div>
					
				<?php endwhile; ?>
				</div>
				<?php endif; ?>
				
			</div>
			<?php get_sidebar(); ?>
		</div>
		
		<?php else : // when there is no blog posts ?>
		
		<?php get_template_part('404','splash'); ?>
		
		<?php endif; ?>
	
	</div>
	
</div>

<?php get_template_part('template','footer'); ?>

<?php get_footer(); ?>