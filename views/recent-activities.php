<article class="span12 content-container recent_activity">

	<hgroup class="content-header">
  	<h3><?php echo $recent_act_title;?></h3>
  </hgroup>

	<section class="content-body clearfix">
		<ul class="recent-activity-list">

<?php 

    $activities_text = array(
        'question' => 'Asked this: ',
        'answer'   => 'Answered this: ',
        ''         => 'Commented this: ',
        'comment'  => 'Commented this: ',
        'post'     => 'Posted this: ',
        'guide'    => 'Posted this: '
    );

    $action_text = array(
        'follow' 	=> 'Followed this: ',
        'upvote' 	=> 'Liked this: ',
    	'downvote'	=> 'Disliked this: '
    );

    foreach($activities as $activity):

    	if((isset($activity->comment_parent_author) && count($activity->comment_parent_author)) && ! array_key_exists($activity->action, $action_text)) {
    		
    		$is_reply = true;
    		$act_type_text = ($activity->comment_parent_author[0]->comment_type == 'answer') ? 'answer' : 'comment';
    		$act_text = return_screenname_link($activity->author) . ' Replied to  ' . return_screenname_link($activity->comment_parent_author[0]->user_id) .'\'s ' . $act_type_text . ':';
    		$parent_excerpt = strlen($activity->comment_parent_author[0]->comment_content) > 200 ? substr( truncated_text(sanitize_text($activity->comment_parent_author[0]->comment_content)), 0, 200 ) . "&#8230;" : truncated_text(sanitize_text($activity->comment_parent_author[0]->comment_content));
    		
    		
    	} else {
    		
    		$is_reply = false;
    		$activity_text = (array_key_exists($activity->action, $action_text)) ? $action_text[$activity->action] : $activities_text[$activity->type];
    		$act_text =  return_screenname_link( $activity->author ) . ' ' . $activity_text;
    	}
        	

        $excerpt = '<li class="recent-activity_excerpt">' . ( strlen( $activity->content ) > 200 ? substr( truncated_text(sanitize_text($activity->content)), 0, 200 ) . "&#8230;" : truncated_text(sanitize_text($activity->content))) . '</li>';

        $time_options = array(
            "timestamp"   => strtotime( $activity->date ),
            "date_format" => 'M j',
            "time_format" => 'g:ia',
            "separator"   => 'space'
        );

?>
					<li class="clearfix">
						<div class="recent-activity_avatar">
							<?php echo profile_photo( $activity->author ); ?>
						</div>
						<div class="recent-activity_data">
							<ul>
								<li class="recent-activity_activity"><?php echo $act_text;?></li>
								<li class="recent-activity_title"><a href="<?php echo (in_array($activity->type, $recent->comment_types) || $activity->type == '') ? get_permalink($activity->post->ID) : get_permalink($activity->ID) ;?>"><?php echo (in_array($activity->type, $recent->comment_types) || $activity->type == '') ? (($is_reply) ? '"' . $parent_excerpt . '"' : truncated_text(sanitize_text($activity->post->post_title))) : truncated_text(sanitize_text($activity->title));?></a></li>
								<?php if (in_array($activity->type, $recent->comment_types) || $activity->type == '') {echo $excerpt;} ?>
								<li class="recent-activity_category-time">
									<?php get_partial( 'parts/space_date_time', $time_options ); ?>
									<a href="<?php echo (in_array($activity->type, $recent->comment_types)) ? ((count($activity->post->category)) ? get_term_link($activity->post->category[0]) : null) : ((count($activity->category)) ? get_term_link($activity->category[0]) : null) ;?>" class="category"><?php echo (in_array($activity->type, $recent->comment_types)) ? ((count($activity->post->category)) ? $activity->post->category[0]->cat_name : 'Uncategorized') : ((count($activity->category)) ? $activity->category[0]->cat_name : 'Uncategorized') ;?></a>
								</li>
							</ul>
						</div>
					</li>

<?php
    endforeach; 
    
?>
		</ul>
		
	</section>			

</article>