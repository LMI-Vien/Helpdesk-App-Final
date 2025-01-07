<?php
function set_timezone() {
    date_default_timezone_set('Asia/Manila'); 
    $ph_time = date('M-d-Y H:i:s');
    return $ph_time;
}