<?php

namespace App\Common;

use Illuminate\Support\Facades\Storage;
use App\TranslateGroup;
use App\TranslateText;
use App\Category;
use App\Language;

Class Helper{

	public static function readFileXML($file, $category, $group){

        // get $language from $group
        $outputList 	= [];
        $outputListFile = [];
        $fileName 		= $file->getClientOriginalName();

        $languageList = TranslateGroup::getLanguages($group);
        $outputList['none'] = [];
        foreach($languageList as $lang){
            $outputList[$lang['code']] = [];
        }

        $xml = \XmlParser::load($file);

        foreach($xml->getContent()->string as $string){
            $text       = '';
            $keyword    = '';
            // content file upload
            $outObj         = new OutPut();
            $outObj->text   = $string->__toString();
            $text = $outObj->text;
            foreach($string->attributes() as $a => $b){
                if($a == "name"){
                    $outObj->name = $b->__toString();
                    $keyword = $outObj->name;
                    break;
                }
            }
            $outputList['none'][] = $outObj;

            // output data
            foreach($languageList as $lang){
                $outObj         = new OutPut();

                $translateText = TranslateText::findByKeyword($keyword, $category, $lang['id'], $text);
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
                $outputList[$lang['code']][] = $outObj;
            }
        }

        $outputListFile['none'] = Helper::storeFileByExt($outputList['none'], 'none', $fileName, 'xml');
        foreach($languageList as $lang){
            $outputListFile[$lang['code']] = Helper::storeFileByExt($outputList[$lang['code']], $lang['code'], $fileName, 'xml');
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
            $outputList[$lang['code']] = [];
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

                $translateText = TranslateText::findByKeyword($slug, $category, $lang['id'], $obj['text']);
                if($translateText){
                    $outObj->text  = $translateText->trans_text;
                }else{
                    $outObj->text  = 'Find not found!';
                }
                if(isset($obj['name'])){
                    $outObj->name = $obj['name'];
                }
                $outputList[$lang['code']][] = $outObj;
            }
        }

        $outputListFile['none'] = Helper::storeFileByExt($outputList['none'], 'none', $fileName, 'json');
        foreach($languageList as $lang){
            $outputListFile[$lang['code']] = Helper::storeFileByExt($outputList[$lang['code']], $lang['code'], $fileName, 'json');
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

    public static function ExportFileExcel2($arrayList){
        \Excel::create('translate', function($excel) use ($arrayList) {

            $excel->sheet('Worksheet', function($sheet) use ($arrayList) {

                $sheet->fromArray($arrayList->toArray());

            });

        })->export('xlsx');
    }

    public static function ReadFileExcel2($file, $category){
        ini_set('memory_limit', '2048M');
        $returnCheck = \Excel::load($file)->skipRows(1)->takeRows(1)->get();

        if(!method_exists($returnCheck, 'getHeading')){
            return back()->with(['flash_message_err_and_reload' => 'The File is not formatted correctly']);
        }else{
            $dataCheck = $returnCheck->getHeading();
            if(!(in_array('keyword',$dataCheck, TRUE) && 
                in_array('source_text',$dataCheck, TRUE) && 
                in_array('translated_text',$dataCheck, TRUE) && 
                in_array('in_language',$dataCheck, TRUE) && 
                in_array('category',$dataCheck, TRUE) && 
                in_array('status',$dataCheck, TRUE))){
                return back()->with(['flash_message_err_and_reload' => 'The File is not formatted correctly']);
            }

            // if file is ok, then load all data and process
            $results = \Excel::load($file);
            $data = $results->toArray();
            $time_created = date('Y-m-d H:i:s');
            $user_create  = \Auth::user()->id;

            if (count($data) > 0) {
                // Kiểm tra xem file có đúng định dạng như file Example ko
                if (isset($data[0]['keyword']) && isset($data[0]['source_text']) && isset($data[0]['category']) && isset($data[0]['status'])) {
                    $list_check = false;

                    // lay du lieu trong ban category va language ra day
                    $categories = Category::pluck('id', 'name');
                    $languages  = Language::select('id')->get();

                    foreach ($data as $row) {
                        foreach($languages as $lang){
                            $checkExist = TranslateText::checkExist(
                                                $row['keyword'], 
                                                $categories[$row['category']], 
                                                $lang['id']);
                            $list_check = true;
                            if(!$checkExist){
                                // if not exist then add to database
                                $translate = new TranslateText;

                                $translate->keyword = $row['keyword'];
                                $translate->source_text = $row['source_text'];
                                $translate->trans_text = '';
                                $translate->language_id = $lang['id'];
                                $translate->category_id = $categories[$row['category']];
                                $translate->translate_type = Helper::getValueStatus($row['status']);
                                $translate->created_by = $user_create;
                                $translate->created_at = $time_created;
                                $translate->updated_by = $user_create;
                                $translate->updated_at = $time_created;

                                $translate->save();
                                $list_check = true;
                            }
                        }
                    }
                    return $list_check;
                }else{
                    return null;
                }
            }else{
                return null;
            }
        }
    }

    public static function ImportFileExcel2($file, $category){
        ini_set('memory_limit', '2048M');
        $returnCheck = \Excel::load($file)->skipRows(1)->takeRows(1)->get();

        if(!method_exists($returnCheck, 'getHeading')){
            return back()->with(['flash_message_err_and_reload' => 'The File is not formatted correctly']);
        }else{
            $dataCheck = $returnCheck->getHeading();
            if(!(in_array('keyword',$dataCheck, TRUE) && 
                in_array('source_text',$dataCheck, TRUE) && 
                in_array('translated_text',$dataCheck, TRUE) && 
                in_array('in_language',$dataCheck, TRUE) && 
                in_array('category',$dataCheck, TRUE) && 
                in_array('status',$dataCheck, TRUE))){
                return back()->with(['flash_message_err_and_reload' => 'The File is not formatted correctly']);
            }

            // if file is ok, then load all data and process
            $results = \Excel::load($file);
            $data = $results->toArray();
            $time_created = date('Y-m-d H:i:s');
            $user_create  = \Auth::user()->id;

            if (count($data) > 0) {
                // Kiểm tra xem file có đúng định dạng như file Example ko
                if (isset($data[0]['keyword']) && isset($data[0]['translated_text']) && isset($data[0]['in_language']) && isset($data[0]['category']) && isset($data[0]['status'])) {
                    $list_check = false;

                    // lay du lieu trong ban category va language ra day
                    $categories = Category::pluck('id', 'name');
                    $languages  = Language::pluck('id', 'name');

                    foreach ($data as $row) {
                        $cate_id = $categories[$row['category']];
                        $lang_id = $languages[$row['in_language']];

                        $checkExist = TranslateText::checkExist(
                                            $row['keyword'], 
                                            $cate_id, 
                                            $lang_id);
                        $list_check = true;

                        if($checkExist){
                            if(strlen($row['translated_text']) > 0){
                                $updateTranslate = TranslateText::updateTranslate(
                                                    $row['keyword'], 
                                                    $cate_id, 
                                                    $lang_id,
                                                    $row['translated_text']
                                                );
                            }else{
                                $updateTranslate = TranslateText::updateTranslate(
                                                    $row['keyword'], 
                                                    $cate_id, 
                                                    $lang_id,
                                                    $row['translated_text'],
                                                    0
                                                );
                            }
                            $list_check = true;
                        }
                    }
                    return $list_check;
                }else{
                    return null;
                }
            }else{
                return null;
            }
        }
    }

    public static function getValueStatus($status){
        switch ($status) {
            case 'Auto':
                return 0;
                break;

            case 'Contributor':
                return 1;
                break;

            case 'Confirmed':
                return 2;
                break;
            
            default:
                return 0;
                break;
        }
    }
}