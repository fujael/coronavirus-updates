<?php
/* Plugin Name: CoronaVirus Updates
 * Plugin URI:  http://computegen.com/corona-board
 * Description: Shows the global pandemic coronavirus(Covid-19) information to your wordpress widget and shortcoded for post
 * Version:     1.0.0
 * Author:      ComputeGen
 * Author URI:  http://computegen.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

include_once dirname( __FILE__ ) . '/lib/WPWidgetFujael.php';
include_once dirname( __FILE__ ) . '/lib/ApiClass.php';

class coronawidget extends WPWidgetFujael {

    /**
     *
     * @var ApiClass 
     */
    var $api;
    public function __construct() {
        $this->Initialize('Corona Statistics', 'coronawidget', 'Displays coronavirus live statistics to your sidebar.');
        $this->AddFieldText('title', 'Title', 'Coronavirus Updates');
        $this->AddFieldTextArea('before_html', 'Before Widget(HTML)', '');
        $this->AddFieldTextArea('after_html', 'After Widget(HTML)', '');
        $this->api = new ApiClass();
        $this->api->initDataLatest();
        
    }

//save the widget settings

    

    public function widget_contents($args, $instance) {
        $this->api->showHtmlWidget();
    }
    

}

add_action('widgets_init', function() {
    register_widget('coronawidget');
});

add_shortcode( 'CoronaUpdates', function ( $atts ) {
    $atts = shortcode_atts( array(
        'field' => 'Total',
    ), $atts, 'CoronaTracker' );
 
    $api = new ApiClass();
    $api->initDataLatest();
    return $api->getLatestField($atts['field'], $atts);
});
