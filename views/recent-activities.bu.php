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
        'follow' => 'Followed this: ',
        'upvote' => 'Liked this: '
    );

    foreach($activities as $activity):

        $activity_text = (array_key_exists($activity->action, $action_text)) ? $action_text[$activity->action] : $activities_text[$activity->type];

        $excerpt = '<li class="recent-activity_excerpt">' . ( strlen( $activity->content ) > 200 ? substr( $activity->content, 0, 200 ) . "&#8230;" : $activity->content ) . '</li>';

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
								<li class="recent-activity_activity"><?php echo return_screenname_link( $activity->author ); ?> <?php echo $activity_text; ?></li>
								<li class="recent-activity_category-time">
									<?php get_partial( 'parts/space_date_time', $time_options ); ?>
									<a href="<?php echo (in_array($activity->type, $recent->comment_types)) ? ((count($activity->post->category)) ? get_term_link($activity->post->category[0]) : null) : ((count($activity->category)) ? get_term_link($activity->category[0]) : null) ;?>" class="category"><?php echo (in_array($activity->type, $recent->comment_types)) ? ((count($activity->post->category)) ? $activity->post->category[0]->cat_name : 'Uncategorized') : ((count($activity->category)) ? $activity->category[0]->cat_name : 'Uncategorized') ;?></a>
								</li>
								<li class="recent-activity_title"><a href="<?php echo (in_array($activity->type, $recent->comment_types)) ? get_permalink($activity->post->ID) : get_permalink($activity->ID) ;?>"><?php echo (in_array($activity->type, $recent->comment_types)) ? $activity->post->post_title : $activity->title;?></a></li>
								<?php if (in_array($activity->type, $recent->comment_types)) {echo $excerpt;} ?>
							</ul>
						</div>
					</li>

<?php
    endforeach; 
?>
		</ul>
		
	</section>			

</article>


<!--         </ol>
</section> -->