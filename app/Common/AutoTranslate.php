<?php

namespace App\Common;

Class AutoTranslate{
    public $api_key 	= 'AIzaSyAFbh0qzvIZmYAfJNB82gTJRg--eam1BpU';
	public $text 			= '';
	public $source			= '';
	public $target			= '';

	public function __construct($text, $source, $target)
    {
        $this->text 		= $text;
        $this->source 		= $source;
        $this->target 		= strtolower($target);
    }

    public function callApi(){
    	$url = 'https://www.googleapis.com/language/translate/v2?key=' . $this->api_key . '&q=' . rawurlencode($this->text);
	    $url .= '&target='.$this->target;
	    if($this->source)
	     $url .= '&source='.$this->source;
	 
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($ch);                 
	    curl_close($ch);
	 
	    $obj =json_decode($response,true); //true converts stdClass to associative array.
	    return $obj;
    }
}