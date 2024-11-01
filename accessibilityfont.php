
<?php
/*
Plugin Name: Accessibility Font
Plugin URI: http://aquicoco.com/accessibility-font/
Description: Añade un widget a la web con varios tamaños de letra para personas con discapacidad visual.
Author: Mario Gonher
Version: 1.1
Author URI: http://www.aquicoco.com
*/
class widget_accesstxt extends WP_Widget {
  /** constructor */
  function widget_accesstxt() {
    parent::WP_Widget(false, $name = 'Accessibility Font');	
  }
  /** @see WP_Widget::widget */
  function widget($args, $instance) {
    extract( $args );
    $title = apply_filters('widget_title', $instance['title']);
    $tags = str_replace(" ", "", $instance['tags']); // remove whitespaces             
    $fontsize = str_replace(" ", "", $instance['fontsize']); // remove whitespaces
    $afontsize = explode(",", $fontsize); // transform into arrays
    $controls = explode(",", str_replace(" ", "", $instance['controls'])); // remove whitespaces then transform into arrays
    
    echo $before_widget;
    if ( $title ) echo $before_title . $title . $after_title;
    ?>
    <script type="text/javascript">
		//Specify affected tags. Add or remove from list
		var tgs = new Array(<?php echo "'" . str_replace(",", "','", $tags) . "'"; ?>);
		//Specify spectrum of different font sizes
		var szs = new Array(<?php echo "'" . str_replace(",", "','", $fontsize) . "'"; ?>);
		var startSz = 2;
		function ts( trgt,inc ) {
			if (!document.getElementById) return
			var d = document,cEl = null,sz = startSz,i,j,cTags;
			sz = inc;
			if ( sz < 0 ) sz = 0;
			if ( sz > 6 ) sz = 6;
			startSz = sz;
			if ( !( cEl = d.getElementById( trgt ) ) ) cEl = d.getElementsByTagName( trgt )[ 0 ];
			cEl.style.fontSize = szs[ sz ];
			for ( i = 0 ; i < tgs.length ; i++ ) {
				cTags = cEl.getElementsByTagName( tgs[ i ] );
				for ( j = 0 ; j < cTags.length ; j++ ) cTags[ j ].style.fontSize = szs[ sz ];
			}
		}
		</script>
    <ul>
      <li><?php
      $controlscount = count($controls);
      foreach ($afontsize as $key => $value) {
        $icontrols = ($controlscount > 1 ? $key : 0);
        echo "<a href=\"javascript:ts('body'," . $key . ")\" style=\"font-size:" . $value . "\">" . $controls[$icontrols] . "</a>&nbsp;&nbsp;";
      }
      ?></li>
    </ul>
    <?php echo $after_widget; ?>
    <?php
  }
  /** @see WP_Widget::update -- do not rename this */
  function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['tags'] = strip_tags($new_instance['tags']);
		$instance['fontsize'] = strip_tags($new_instance['fontsize']);  
		$instance['controls'] = strip_tags($new_instance['controls']);
    return $instance;
  }
  /** @see WP_Widget::form -- do not rename this */
  function form($instance) {
    $title = esc_attr($instance['title']);
    $tags = ($instance['tags'] == "" ? "body,p,li,td, h1, h2, h3, h4, h5" : esc_attr($instance['tags']));
    $fontsize = ($instance['fontsize'] == "" ? "100%, 125%, 150%" : esc_attr($instance['fontsize']));
    $controls = ($instance['controls'] == "" ? "A" : esc_attr($instance['controls']));
  ?>
  <p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
  </p>
  
  <p>
    <label for="<?php echo $this->get_field_id('fontsize'); ?>"><?php _e('Set to these sizes:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('fontsize'); ?>" name="<?php echo $this->get_field_name('fontsize'); ?>" type="text" value="<?php echo $fontsize; ?>" /><br />
    Separa las etiquetas con comas (,)
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('controls'); ?>"><?php _e('Set controller text:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('controls'); ?>" name="<?php echo $this->get_field_name('controls'); ?>" type="text" value="<?php echo $controls; ?>" /><br />
    Separa los controladores de texto con comas (,)
  </p>
  <?php
  }
} // end class widget_accesstxt
add_action('widgets_init', create_function('', 'return register_widget("widget_accesstxt");'));
?>