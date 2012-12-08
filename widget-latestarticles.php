<?php
/*
Plugin Name: Latest Articles 
Plugin URI: http://iviewsource.com
Description: Display list of recent posts in a prettier way
Author: Ray Villalobos
Version: 1.0
Author URI: http://iviewsource.com
*/

class LatestArticles extends WP_Widget {

  function LatestArticles() {
    $widget_options = array(
      'classname' => 'LatestArticles',
      'Description' => 'Create a list of recent posts that looks a bit better than the original'
    );

    parent::WP_Widget('latest_articles' , 'Latest Articles', $widget_options);
  }

  function widget($args,$instance) {
    //Creates the UI
    extract($args, EXTR_SKIP);
    $title = ($instance['title']) ? $instance['title']: 'Latest Articles';
    $body = ($instance['body']) ? $instance['body']: '';
    $number = ($instance['number']) ? $instance['number']: 5;

		$r = new WP_Query(
			apply_filters(
				'widget_posts_args',
				array(
					'posts_per_page' => $number,
					'no_found_rows' => true,
					'post_status' => 'publish',
					'ignore_sticky_posts' => true 
				)
			));


		if ($r->have_posts()) :
	    echo $before_widget;
			if ( $title ) echo $before_title . $title . $after_title;
			if ( $body ) echo '<p>',$body,'</p>';


			print '<link rel="stylesheet" href="/wp-content/plugins/LatestArticles/latestarticles.css" />';

	    echo "\r<ul>\r";
	
			while ($r->have_posts()) :
					$r->the_post();
					echo '<li>';
					
					$category = get_the_category();
					if($category[1]):
						echo '<h3 class="'.$category[0]->slug.'">', '<a href="'.get_category_link($category[1]->term_id ).'">'.$category[1]->cat_name.' <span class="count">('.$category[1]->category_count.')</span></a>','</h3>';
					elseif($category[0]):
						echo '<h3 class="'.$category[0]->slug.'">', '<a href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.' <span class="count">('.$category[0]->category_count.')</span></a>','</h3>';
					endif;
	
					echo '<p><a href="', the_permalink(),'" title="', esc_attr(get_the_title() ? get_the_title() : get_the_ID()),'">';

					if (get_the_title()):
						echo the_title();
					else :
						echo the_ID();
					endif;
					
					echo '</p></a>';

					if (get_the_date()):
						echo '<div class="articleinfo">';
						echo the_date('M d', '<span class="date">', '</span>');
					endif;

					if (get_the_author()):
						echo '<span class="author">by: ', get_the_author_link(),'</span>';
						echo '</div>';
					endif;

					echo '</li>';
			endwhile;
	
	    echo "\r</ul>\r";
	    echo $after_widget;
		endif; // have posts
    
  }

	function form($instance) {
		//Title
		echo '<p><label for="',$this->get_field_id('title'),'">','Title','</label><br />';
		echo '<input ';
		echo 'id="', $this->get_field_id('title'), '" ';
		echo 'name="', $this->get_field_name('title'), '" ';
		echo 'value="', esc_attr($instance['title']), '"','></p>';
		
		//How Many Articles
		echo '<p><label for="',$this->get_field_id('number'),'">','How many','</label><br />';	
		echo '<input ';
		echo 'id="', $this->get_field_id('number'), '" ';
		echo 'name="', $this->get_field_name('number'), '" ';
		echo 'value="', esc_attr($instance['number']), '"','>';
		
		//Body
		echo '<p><label for="',$this->get_field_id('body'),'">','Description','</label><br />';
		echo '<textarea ';
		echo 'id="', $this->get_field_id('body'), '" ';
		echo 'name="', $this->get_field_name('body'), '" >';
		echo $instance['body'],'</textarea></p>';
	}
} 

function init_latest_articles() {
  register_widget("LatestArticles");
}

add_action('widgets_init' , 'init_latest_articles');

?>