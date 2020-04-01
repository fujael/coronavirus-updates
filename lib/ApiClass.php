<?php

/**
 * ApiClass used to grab updated data from api server
 *
 * @author User
 */
class ApiClass {
    /**
     *
     * @var string 
     */
    const BASE = 'https://www.fujael.com/wp-json';
    /**
     *
     * @var array() 
     */
    var $data;
    /**
     *
     * @var boolean 
     */
    var $from;
    function initDataLatest() {
        if (!$this->getData('updates_coronavirus_latest', '/updates/coronavirus/latest')) {
            return;
        }
    }
    function getFromApi($option, $path) {
        $this->from = 'api';
        $string = file_get_contents(self::BASE.$path);
        if (!$this->data = json_decode($string, true)) {
            return false;
        }
        add_option('CB_time_'.$option, time());
        return add_option('CB_'.$option, $string);
    }
    const CACHE_EXPIRE = 180;
    function getFromDB($option) {
        $this->from = 'db';
        $time = (int) get_option('CB_time_updates_coronavirus_latest');
        if (!$time || (time() - $time) > self::CACHE_EXPIRE) {
            return false;
        }
        $a = get_option('CB_'.$option);
        if (!empty($a)) {
            $this->data = json_decode($a, true);
            return $this->data !== false;
        }
        return false;
    }
    function getData($option, $path) {
        if (!$this->getFromDB($option)) {
            $this->getFromApi($option, $path);
        }
    }
    function showHtmlWidget() {
        if (!$this->data) {
            return;
        }
        echo "\n";
        $this->getStyleCssWidget();
        echo '<table class="cov-19">';
        echo '<tbody>';
        foreach ($this->data as $key => $value) {
            $pieces = preg_split('/(?<=\\w)(?=[A-Z])/',$key);
            $label = implode(' ', $pieces);
            echo '<tr>';
            echo '<th>';
            echo $label;
            echo '</th>';
            echo '<td>';
            echo $value;
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
    /**
     *
     * @var boolean 
     */
    var $defaultStyle = true;
    function getStyleCssWidget() {
        if (!$this->defaultStyle) {
            return;
        }
        ?><style type="text/css">
table.cov-19 {
    font-size: 1.1em;
    border: none;
}
table.cov-19 th, table.cov-19 td {
    border: none;
    border-bottom: 1px solid #DDD;
    padding: 1rem;
}
table.cov-19 td{
    width: 25%;
}
</style><?PHP
    }
    function getLatestField($field, $default = '') {
        if (empty($this->data[$field])) {
            return $default;
        }
        return $this->data[$field];
    }
    
}
