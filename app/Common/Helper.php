<?php

namespace App\Common;

use Illuminate\Support\Facades\Storage;
use App\TranslateGroup;
use App\TranslateText;

Class Helper{

	public static function readFileXML($file, $category, $group){

        // get $language from $group
        $outputList 	= [];
        $outputListFile = [];
        $fileName 		= $file->getClientOriginalName();

        $languageList = TranslateGroup::getLanguages($group);
        $outputList['none'] = [];
        foreach($languageList as $lang){
            $outputList[$lang->code] = [];
        }

        $xml = \XmlParser::load($file);

        foreach($xml->getContent()->string as $string){
            // content file upload
            $outObj         = new OutPut();
            $outObj->text  = $string->__toString();
            foreach($string->attributes() as $a => $b){
                if($a == "name"){
                    $outObj->name = $b->__toString();
                    break;
                }
            }
            $outputList['none'][] = $outObj;

            // output data
            foreach($languageList as $lang){
                $outObj         = new OutPut();

                $slug = str_slug($string->__toString(), '_');

                $translateText = TranslateText::findBySlug($slug, $category, $lang->id, $string->__toString());
                if($translateText){
                    $outObj->text  = $translateText->trans_text;
                }else{
                    $outObj->text  = 'Find not found!';
                }

                foreach($string->attributes() as $a => $b){
                    if($a == "name"){
                        $outObj->name = $b->__toString();
                        break;
                    }
                }
                $outputList[$lang->code][] = $outObj;
            }
        }

        $outputListFile['none'] = Helper::storeFileByExt($outputList['none'], 'none', $fileName, 'xml');
        foreach($languageList as $lang){
            $outputListFile[$lang->code] = Helper::storeFileByExt($outputList[$lang->code], $lang->code, $fileName, 'xml');
        }
        return $outputListFile;
    }

    public static function readFileJSON($file, $category, $group){
        // get $language from $group
        $outputList     = [];
        $outputListFile = [];
        $fileName       = $file->getClientOriginalName();

        $languageList = TranslateGroup::getLanguages($group);
        $outputList['none'] = [];
        foreach($languageList as $lang){
            $outputList[$lang->code] = [];
        }

        $string = file_get_contents($file);
        $string = str_replace(array("\r", "\n", "\t"), "", $string);
        $objs = json_decode($string, true);
        
        foreach ($objs as $obj) {
            // content file upload
            $outObj         = new OutPut();
            $outObj->text  = $obj['text'];

            if(isset($obj['name'])){
                $outObj->name = $obj['name'];
            }
            $outputList['none'][] = $outObj;

            $obj['slug'] = str_slug($obj['text'], '_');
            $return_data[] = $obj;
            foreach($languageList as $lang){
                $outObj         = new OutPut();

                $slug = str_slug($obj['text'], '_');

                $translateText = TranslateText::findBySlug($slug, $category, $lang->id, $obj['text']);
                if($translateText){
                    $outObj->text  = $translateText->trans_text;
                }else{
                    $outObj->text  = 'Find not found!';
                }
                if(isset($obj['name'])){
                    $outObj->name = $obj['name'];
                }
                $outputList[$lang->code][] = $outObj;
            }
        }

        $outputListFile['none'] = Helper::storeFileByExt($outputList['none'], 'none', $fileName, 'json');
        foreach($languageList as $lang){
            $outputListFile[$lang->code] = Helper::storeFileByExt($outputList[$lang->code], $lang->code, $fileName, 'json');
        }
        return $outputListFile;
    }

    public static function storeFileByExt($array, $lang, $fileName, $ext){
    	switch ($ext) {
    		case 'xml':
    			return Helper::buildFileXML($array, $lang, $fileName);
    			break;
    		
    		case 'json':
    			return Helper::buildFileJSON($array, $lang, $fileName);
    			break;
    		
    		default:
    			return null;
    			break;
    	}
    }

    public static function buildFileXML($array, $lang, $fileName){
    	$xml = new \SimpleXMLElement("<?xml version=\"1.0\"?><resources></resources>");

    	return Helper::array2XML($array, $lang, $fileName, $xml);
    }

    public static function buildFileJSON($array, $lang, $fileName){
        $jsondata = json_encode($array, JSON_PRETTY_PRINT);


        $urlUserFolder  = public_path() . '/uploads/' . \Auth::id();
        if($lang != 'none'){
            $urlFolder      = public_path() . '/uploads/' . \Auth::id() .'/values-'. strtolower($lang);
        }else{
            $urlFolder      = public_path() . '/uploads/' . \Auth::id() .'/values';
        }
        $urlFile        = $urlFolder . '/' . $fileName;

        Helper::checkFolder($urlUserFolder);
        Helper::checkFolder($urlFolder);

        $fh = fopen($urlFile, 'w');
        fwrite($fh, $jsondata);
        fclose($fh);

        if($lang == 'none'){
            return 'values/' . $fileName;
        }
        return 'values-'. strtolower($lang) . '/' . $fileName;
    }

    public static function array2XML($array, $lang, $fileName, &$xml){
    	foreach($array as $obj) {
	        $string = $xml->addChild('string', $obj->text);
	        $string->addAttribute('name', $obj->name);
	    }

	    $urlUserFolder 	= public_path() . '/uploads/' . \Auth::id();
        if($lang != 'none'){
    	    $urlFolder 		= public_path() . '/uploads/' . \Auth::id() .'/values-'. strtolower($lang);
        }else{
            $urlFolder      = public_path() . '/uploads/' . \Auth::id() .'/values';
        }
	    $urlFile 		= $urlFolder . '/' . $fileName;

        Helper::checkFolder($urlUserFolder);
        Helper::checkFolder($urlFolder);

	    $fh = fopen($urlFile, 'w');
		fwrite($fh, $xml->asXML());
		fclose($fh);

        if($lang == 'none'){
            return 'values/' . $fileName;
        }
        return 'values-'. strtolower($lang) . '/' . $fileName;
    }

    public static function zip($files = array()) {
        $fileSource = public_path() . '/zipfile/zip.zip';
        $urlUserFolder  = public_path() . '/uploads/' . \Auth::id();
        $urlZipFolder   = public_path() . '/uploads/' . \Auth::id() .'/zip';
        $urlZipFile   = public_path() . '/uploads/' . \Auth::id() .'/zip/archive.zip';

        if (!file_exists($urlUserFolder)) {
            // ShowAlert: user must be upload file
            return '';
        }else{
            Helper::checkFolder($urlZipFolder);
        }

        if (!file_exists($urlZipFile)) {
	        copy($fileSource, $urlZipFile);
	        chmod($urlZipFile, 0777);
	    }

        $zip = new \ZipArchive();
        if ($zip->open($urlZipFile, \ZipArchive::CREATE) === TRUE)
        {
            foreach ($files as $file) {
                $zip->addFile(public_path() . '/uploads/' . \Auth::id() .'/'. $file, $file);
            }
            $zip->close();
            return $urlZipFile;
        }else{
            return '';
        }
    }

    public static function checkFolder($dir){
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        return true;
    }

    public static function exportList($list){
        dd($list);
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\"?><resources></resources>");

        dd(Helper::array2XML($list, 'admin_export', 'export_xml', $xml));
    }
}