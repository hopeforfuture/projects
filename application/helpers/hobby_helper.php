<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('fetchallhobby'))
{
    function fetchallhobby()
    {
        $ci=& get_instance();
        $ci->defaultdata->setTable('hobby');
        $hobby_list = $ci->defaultdata->selectdata(array());
        $ci->defaultdata->unsetTable();
        $hobbyarr = array();

        if(count($hobby_list) > 0)
        {
        	foreach($hobby_list as $hl)
        	{
        		$hobby_id = $hl->hb_id;
        		$hobby_name = $hl->hb_name;

        		$hobbyarr[$hobby_id] = $hobby_name;
        	}
        }

        return $hobbyarr; 
    }   
}