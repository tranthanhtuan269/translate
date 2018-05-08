<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranslateGroup extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'description'];

    public static function getAllTranslateGroup()
    {
        $sql  = "SELECT group_table.id, group_table.name, group_table.description, GROUP_CONCAT(group_table.languageName SEPARATOR ', ') as languages FROM (SELECT translate_groups.id, translate_groups.name, translate_groups.description, languages.name as languageName FROM translate_groups LEFT JOIN translate_group_language ON translate_group_language.translate_group_id = translate_groups.id LEFT JOIN languages ON translate_group_language.language_id = languages.id) group_table GROUP BY group_table.id, group_table.name, group_table.description";
        return \DB::select($sql);
    }

    public static function getDataForDatatable(){
        $sql  = "SELECT group_table.id, group_table.name, group_table.description, GROUP_CONCAT(group_table.languageName SEPARATOR ', ') as languages FROM (SELECT translate_groups.id, translate_groups.name, translate_groups.description, languages.name as languageName FROM translate_groups LEFT JOIN translate_group_language ON translate_group_language.translate_group_id = translate_groups.id LEFT JOIN languages ON translate_group_language.language_id = languages.id) group_table GROUP BY group_table.id, group_table.name, group_table.description";
        $query = \DB::select($sql);
        return collect($query);
    }

    public static function getLanguages($group){
        $sql = 'SELECT languages.id, languages.name, languages.code FROM translate_group_language JOIN languages ON translate_group_language.language_id = languages.id WHERE translate_group_language.translate_group_id = ' . $group;
        $query = \DB::select($sql);
        return collect($query);
    }

    public static function deleteMulti($id_list){
        $list = explode(",",$id_list);
        $checkGroup = TranslateGroup::whereIn('id', $list);
        return ($checkGroup->delete() > 0);
    }
}
