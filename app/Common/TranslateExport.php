<?php

namespace App\Common;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\TranslateText;
use App\Category;

class TranslateExport implements FromQuery, WithHeadings
{
	use Exportable;

	public $search;
	public $category;
	public $language;
	public $status;

	public function headings(): array
    {
        return [
            'Source Text',
            'Translated Text',
            'In Language',
            'Category',
            'Status',
        ];
    }

	public function __construct($search, $category, $language, $status)
    {
        $this->search 		= $search;
        $this->category 	= $category;
        $this->language 	= $language;
        $this->status 		= $status;
    }

    public function query()
    {
        return TranslateText::getDataForExport($this->search, $this->category, $this->language, $this->status);
    }
}