<?php

/**
 * Description of WPWidgetFujael
 *
 * @author Sayel Fujael <sayel@fujael.com>
 */
abstract class WPWidgetFujael extends WP_Widget {
    public function Initialize($title, $classname, $description) {
        $option = array(
            'classname' => $classname,
            'description' => $description,
            'text_domain'
        );
        $this->WP_Widget($classname, $title, $option);
    }
    public function update($new_instance, $old_instance) {
        $instance = array();
        foreach ($this->Field as $field => $conf) {
            $value = '';
            if (!empty($old_instance[$field])) {
                $value = $old_instance[$field];
            }
            if (!empty($new_instance[$field])) {
                $value = $new_instance[$field];
            }
            if($conf['strip_tags']){
                $value = strip_tags($new_instance[$field]);
            }
            $instance[$field] = $value;
            //(!empty($new_instance[$field]) ) ? strip_tags($new_instance[$field]) : '';
        }
        return $instance;
    }
    
    protected function FieldRow($field, $args, $value) {
        ?>
        <p>
                    <?PHP
                    $this->print_label($field, $args);
                    
                    switch ($args['type']){
                        case 'textarea' :
                            $this->field_item_textarea($field, $args, $value);
                            break;
                        case 'text' :
                        default :
                            $this->field_item_text($field, $args, $value);
                            break;
                    }
                    ?>
        </p>
        <?php
    }
    private function field_item_text($field, $args, $value) {
        ?><input class="widefat" id="<?php echo $this->get_field_id($field); ?>" name="<?php echo $this->get_field_name($field); ?>" type="text" value="<?php echo esc_attr($value); ?>"><?PHP
        }
    private function field_item_textarea($field, $args, $value) {
        ?><textarea cols="<?PHP echo $args['rows'] ?>" rows="<?PHP echo $args['rows']; ?>" class="widefat" id="<?php echo $this->get_field_id($field); ?>" name="<?php echo $this->get_field_name($field); ?>" type="text"><?php echo $value; ?></textarea><?PHP
        }
    private function print_label($field, $args) {
        ?><label for="<?php echo $this->get_field_id($field); ?>"><?php _e($args['label']); ?></label><?PHP
        }
    /**
     *
     * @var array() 
     */
    var $Field = [];
    public function AddFieldItem($field, $conf) {
        $this->Field[$field] = array_merge(['type' => 'text', 'strip_tags' => true], $conf);
    }
    public function AddFieldText($field, $label, $default = '') {
        $conf['type'] = 'text';
        $conf['label'] = $label;
        $conf['value'] = $default;
        $this->AddFieldItem($field, $conf);
    }
    public function AddFieldTextArea($field, $label, $default = '') {
        $conf['type'] = 'textarea';
        $conf['label'] = $label;
        $conf['value'] = $default;
        $conf['cols'] = 16;
        $conf['rows'] = 6;
        $this->AddFieldItem($field, $conf);
    }
    //override
    function form($instance) {

        $this->FormFields($instance);
    }
    protected function FormFields($instance) {
        foreach ($this->Field as $field => $conf) {
            if (isset($instance[$field])) {
                $value = $instance[$field];
            } else {
                $value = __($conf['value'], 'text_domain');
            }
            $this->FieldRow($field, $conf, $value);
        }
    }
    public function showWidgetBefore($args) {
        echo $args['before_widget'];
        echo "<div class=\"widget\">";
        $this->showWidgetBeforeHTML($instance);
    }
    public function showWidgetTitle($instance) {
        if (!empty($instance['title'])) {
            echo sprintf('<h4 class="widget-title">%s</h4>', $instance['title']);
        }
    }
    public function showWidgetBeforeHTML($instance) {
        if (!empty($instance['before_html'])) {
            echo $instance['before_html'];
        }
    }
    public function showWidgetAfterHTML($instance) {
        if (!empty($instance['after_html'])) {
            echo $instance['after_html'];
        }
    }
    public function showWidgetAfter($args) {
        $this->showWidgetAfterHTML($instance);
        echo "</div>\n";
        echo $args['after_widget'];
    }
    public function widget($args, $instance) {
        $this->showWidgetBefore($args);

        $this->showWidgetTitle($instance);
        $this->widget_contents($args, $instance);

        $this->showWidgetAfter($args);
    }
    abstract public function widget_contents($args, $instance);
}
