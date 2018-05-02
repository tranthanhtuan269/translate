<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranslateGroup extends Model
{
    protected $fillable = ['name', 'description'];

    public static function getAllTranslateGroup()
    {
        $sql  = "SELECT group_table.id, group_table.name, group_table.description, GROUP_CONCAT(group_table.languageName SEPARATOR ', ') as languages FROM (SELECT translate_groups.id, translate_groups.name, translate_groups.description, languages.name as languageName FROM translate_groups JOIN translate_group_language ON translate_group_language.translate_group_id = translate_groups.id JOIN languages ON translate_group_language.language_id = languages.id) group_table GROUP BY group_table.id, group_table.name, group_table.description";
        return \DB::select($sql);
    }

    public static function getAllTranslateGroupbk()
    {
    	$query = \DB::table('translate_groups')
                ->join('translate_group_language', 'translate_group_language.translate_group_id', 'translate_groups.id')
                ->join('languages', 'translate_group_language.language_id', 'languages.id')
                ->select(
                        'translate_groups.id as id',
                        'translate_groups.name as name',
                        'translate_groups.description as description',
                        'languages.name as languageName'
                        )
                ->orderBy('translate_groups.id', 'desc');
        dd($query->get());
        return $query->get();
    }
}
