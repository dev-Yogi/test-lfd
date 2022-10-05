<?php

if( function_exists('register_sidebar'))
{
	register_sidebar(array('name'=> "Home Page - Sidebar",
		'id' => 'home_sidebar',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => ''
	));
}


?>