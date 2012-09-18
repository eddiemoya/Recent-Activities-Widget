<section class="span12">
    <section class="content-container recent-activity">

        <hgroup class="content-header"><h3><?php echo $recent_act_title;?></h3></hgroup>

        <ol class="content-body result clearfix">

<?php 


$activities_text = array('question' 	=> 'Asked this: ',
							'answer'	=> 'Answered this: ',
							''			=> 'Commented this: ',
							'comment'	=> 'Commented this: ',
							'post'		=> 'Posted this: ',
							'guide'		=> 'Posted this: ');
							
$action_text = array('follow' => 'Followed this: ',
					 'upvote' => 'Liked this: ');

    foreach($activities as $activity):

        $activity_text = (array_key_exists($activity->action, $action_text)) ? $action_text[$activity->action] : $activities_text[$activity->type];

        $excerpt = '<article class="excerpt">' . ( strlen( $activity->content ) > 200 ? substr( $activity->content, 0, 200 ) . "&#8230;" : $activity->content ) . '</article>';

        $time_options = array(
            "timestamp"   => strtotime( $activity->date ),
            "date_format" => 'M j',
            "time_format" => 'g:ia',
            "separator"   => 'space'
        );

?>


            <li class="clearfix">
                <?php get_partial( 'parts/crest', array( "user_id" => $activity->author, "width" => 'span4' ) ); ?>
                <div class="span8">
                    <h3>
                        <span><?php echo $activity_text; ?></span>
                        <?php get_partial( 'parts/space_date_time', $time_options ); ?>
                        <a href="<?php echo (in_array($activity->type, $recent->comment_types)) ? ((count($activity->post->category)) ? get_term_link($activity->post->category[0]) : null) : ((count($activity->category)) ? get_term_link($activity->category[0]) : null) ;?>" class="category"><?php echo (in_array($activity->type, $recent->comment_types)) ? ((count($activity->post->category)) ? $activity->post->category[0]->cat_name : 'Uncategorized') : ((count($activity->category)) ? $activity->category[0]->cat_name : 'Uncategorized') ;?></a>

                        <a href="<?php echo (in_array($activity->type, $recent->comment_types)) ? get_permalink($activity->post->ID) : get_permalink($activity->ID) ;?>"><?php echo (in_array($activity->type, $recent->comment_types)) ? sanitize_text($activity->post->post_title) : sanitize_text($activity->title);?></a>
                    </h3>
                    <?php echo (in_array($activity->type, $recent->comment_types)) ? sanitize_text($excerpt) : null; ?>

                </div>
            </li>

<?php
    endforeach; 
?>
        </ol>

    </section>
</section>