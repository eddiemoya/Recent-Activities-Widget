<?php

/*
Plugin Name: Recent Activities
Description: Displays sites most recent activity.
Version: 1.0
Author: Dan Crimmins
*/

class Recent_Activities_Widget extends WP_Widget {
      
    /**
     * Name for this widget type, should be human-readable - the actual title it will go by.
     * 
     * @var string [REQUIRED]
     */
    var $widget_name = 'Recent Activities Widget';
   
    /**
     * Root id for all widgets of this type. Will be automatically generate if not set.
     * 
     * @var string [OPTIONAL]. FALSE by default.
     */
    var $id_base = 'recent_activities_widget';
    
    /**
     * Shows up under the widget in the admin interface
     * 
     * @var string [OPTIONAL]
     */
    private $description = 'Displays site-wide recent activities';

    /**
     * CSS class used in the wrapping container for each instance of the widget on the front end.
     * 
     * @var string [OPTIONAL]
     */
    private $classname = '';
    
    /**
     * Be careful to consider PHP versions. If running PHP4 class name as the contructor instead.
     * 
     * @author Eddie Moya
     * @return void
     */
    public function Recent_Activities_Widget(){
        $widget_ops = array(
            'description' => $this->description,
            'classname' => $this->classname
        );

        parent::WP_Widget($this->id_base, $this->widget_name, $widget_ops);
    }
    
    /**
     * Self-registering widget method.
     * 
     * This can be called statically.
     * 
     * @author Eddie Moya
     * @return void
     */
    public function register_widget() {
        add_action('widgets_init', create_function( '', 'register_widget("' . __CLASS__ . '");' ));
    }
    
    /**
     * The front end of the widget. 
     * 
     * Do not call directly, this is called internally to render the widget.
     * 
     * @author [Widget Author Name]
     * 
     * @param array $args       [Required] Automatically passed by WordPress - Settings defined when registering the sidebar of a theme
     * @param array $instance   [Required] Automatically passed by WordPress - Current saved data for the widget options.
     * @return void 
     */
    public function widget( $args, $instance ){
    	
    	ob_start();
    	
        extract($args);
        extract($instance);
        
     	 // include (get_template_directory_url() . '/classes/communities_profile.php');
       get_template_part('classes/communities_profile');
       
       $category = (! empty($recent_act_cats)) ? $recent_act_cats : null;
        
       $recent = new User_Profile;
        
       $activities = $recent->posts_per_page($recent_act_num)
       						->category($category)
        					->get_all_activities()
        				    ->activities;

        
        include ('views/recent-activities.php');	

        $output = ob_get_clean();
        
        echo $output;
        
    }
    
    /**
     * Data validation. 
     * 
     * Do not call directly, this is called internally to render the widget
     * 
     * @author [Widget Author Name]
     * 
     * @uses esc_attr() http://codex.wordpress.org/Function_Reference/esc_attr
     * 
     * @param array $new_instance   [Required] Automatically passed by WordPress
     * @param array $old_instance   [Required] Automatically passed by WordPress
     * @return array|bool Final result of newly input data. False if update is rejected.
     */
    public function update($new_instance, $old_instance){
        
        /* Lets inherit the existing settings */
        $instance = $old_instance;
        
        
        
        /**
         * Sanitize each option - be careful, if not all simple text fields,
         * then make use of other WordPress sanitization functions, but also
         * make use of PHP functions, and use logic to return false to reject
         * the entire update. 
         * 
         * @see http://codex.wordpress.org/Function_Reference/esc_attr
         */
        foreach($new_instance as $key => $value){
            $instance[$key] = esc_attr($value);
            
        }
        
        
        foreach($instance as $key => $value){
            if($value == 'on' && !isset($new_instance[$key])){
                unset($instance[$key]);
            }

        }
        
        return $instance;
    }
    
    /**
     * Generates the form for this widget, in the WordPress admin area.
     * 
     * The use of the helper functions form_field() and form_fields() is not
     * neccessary, and may sometimes be inhibitive or restrictive.
     * 
     * @author Eddie Moya
     * 
     * @uses wp_parse_args() http://codex.wordpress.org/Function_Reference/wp_parse_args
     * @uses self::form_field()
     * @uses self::form_fields()
     * 
     * @param array $instance [Required] Automatically passed by WordPress
     * @return void 
     */
    public function form($instance){
        
        /* Setup default values for form fields - associtive array, keys are the field_id's */
        $defaults = array('recent_act_title' => 'Recent Activities', 'recent_act_num' => '5', 'recent_act_cats' => '');
        
        /* Merge saved input values with default values */
        $instance = wp_parse_args((array) $instance, $defaults);
        
        $category_terms = $this->get_categories();

        /* Example of multiple inputs at once. */
        $fields = array(
            array(
                'field_id' => 'recent_act_title',
                'type' => 'text',
                'label' => 'Enter Title'
            ),
            array(
                'field_id' => 'recent_act_num',
                'type' => 'select',
                'label' => 'Number of Activities to Display',
                'options' => array( 
                    
                    /* These option associative keys can be whatever you want, 
                     * no spaces or funny characters though. 
                     */
                    '1' => '1',
                    '2' => '2',
            		'3' => '3',
            		'4'	=> '4',
            		'5' => '5',
            		'6' => '6',
            		'7' => '7',
            		'8' => '8',
            		'9' => '9',
            		'10' => '10'
                    )
            ),
            
            array('field_id' => 'recent_act_cats',
                	'type' => 'select',
                	'label' => 'Show categories',
               		'options' => $category_terms
            )
        );

        /* Builds a series of inputs based on the $fields array created above. */
        $this->form_fields($fields, $instance);
        
        /* Examples of input fields one at a time. */
       /* $this->form_field('bp_string', 'text', 'Enter String', $instance);
        $this->form_field('bp_checkbox', 'checkbox', 'Choice 1', $instance);
        $this->form_field('bp_checkbox_2', 'checkbox', 'Choice 2', $instance);
        $this->form_field('bp_textarea', 'textarea', 'Enter Lots of Text', $instance);
        $this->form_field('bp_checkbox_3', 'checkbox', 'Choice 3', $instance);*/
    }
    

    /**
     * Helper function - does not need to be part of widgets, this is custom, but 
     * is helpful in generating multiple input fields for the admin form at once. 
     * 
     * This is a wrapper for the singular form_field() function.
     * 
     * @author Eddie Moya
     * 
     * @uses self::form_fields()
     * 
     * @param array $fields     [Required] Nested array of field settings
     * @param array $instance   [Required] Current instance of widget option values.
     * @return void
     */
    private function form_fields($fields, $instance){
        foreach($fields as &$field){
            extract($field);
            
            $this->form_field($field_id, $type, $label, $instance, $options);
        }
    }
    
    private function get_categories() {
    	
    	$args = array(
					'type'                     => 'post',
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => 'category',
					'pad_counts'               => false );
				
		$cats = get_categories( $args );
		
		$term_opts[''] = 'All Categories';
		
		foreach($cats as $cat) {
			
			$term_opts[$cat->term_id] = $cat->name;
		}
		
			return $term_opts;
    }
    /**
     * Helper function - does not need to be part of widgets, this is custom, but 
     * is helpful in generating single input fields for the admin form at once. 
     *
     * @author Eddie Moya
     * 
     * @uses get_field_id() (No Codex Documentation)
     * @uses get_field_name() http://codex.wordpress.org/Function_Reference/get_field_name
     * 
     * @param string $field_id  [Required] This will be the CSS id for the input, but also will be used internally by wordpress to identify it. Use these in the form() function to set detaults.
     * @param string $type      [Required] The type of input to generate (text, textarea, select, checkbox]
     * @param string $label     [Required] Text to show next to input as its label.
     * @param array $instance   [Required] Current instance of widget option values. 
     * @param array $options    [Optional] Associative array of values and labels for html Option elements.
     * 
     * @return void
     */
    private function form_field($field_id, $type, $label, $instance, $options = array()){
  
        ?><p><?php
        
        switch ($type){
            
            case 'text': ?>
            
                    <label for="<?php echo $this->get_field_id( $field_id ); ?>"><?php echo $label; ?>: </label>
                    <input id="<?php echo $this->get_field_id( $field_id ); ?>" style="<?php echo $style; ?>" class="widefat" name="<?php echo $this->get_field_name( $field_id ); ?>" value="<?php echo $instance[$field_id]; ?>" />
                <?php break;
            
            case 'select': ?>
            
                    <label for="<?php echo $this->get_field_id( $field_id ); ?>"><?php echo $label; ?>: </label>
                    <select id="<?php echo $this->get_field_id( $field_id ); ?>" class="widefat" name="<?php echo $this->get_field_name($field_id); ?>">
                        <?php
                            foreach ( $options as $value => $label ) :  ?>
                        
                                <option value="<?php echo $value; ?>" <?php selected($value, $instance[$field_id]) ?>>
                                    <?php echo $label ?>
                                </option><?php
                                
                            endforeach; 
                        ?>
                    </select>
                    
				<?php break;
                
            case 'textarea':
                
                $rows = (isset($options['rows'])) ? $options['rows'] : '16';
                $cols = (isset($options['cols'])) ? $options['cols'] : '20';
                
                ?>
                    <label for="<?php echo $this->get_field_id( $field_id ); ?>"><?php echo $label; ?>: </label>
                    <textarea class="widefat" rows="<?php echo $rows; ?>" cols="<?php echo $cols; ?>" id="<?php echo $this->get_field_id($field_id); ?>" name="<?php echo $this->get_field_name($field_id); ?>"><?php echo $instance[$field_id]; ?></textarea>
                <?php break;
            
            case 'radio' :
                /**
                 * Need to figure out how to automatically group radio button settings with this structure.
                 */
                ?>
                    
                <?php break;
            
            case 'checkbox' : ?>
                    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id($field_id); ?>" name="<?php echo $this->get_field_name($field_id); ?>"<?php checked( (!empty($instance[$field_id]))); ?> />
                	<label for="<?php echo $this->get_field_id( $field_id ); ?>"><?php echo $label; ?></label>
                <?php
        }
        
        ?></p><?php
    }
}

Recent_Activities_Widget::register_widget();