<?php

if (!function_exists('get_img_name')) {
    /*
     * get img name
     */
    function get_img_name($img_file_name)
    {
        return preg_replace("/^.*\/([^\/]*)\.[^.\/]*$/", '$1', $img_file_name);
    }
}
