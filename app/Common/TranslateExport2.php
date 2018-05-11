<?php

namespace App\Common;

use App\TranslateText;
use App\Category;

class TranslateExport2
{
	public $search;
	public $category;
	public $language;
	public $status;

	public function __construct($search, $category, $language, $status)
    {
        $this->search 		= $search;
        $this->category 	= $category;
        $this->language 	= $language;
        $this->status 		= $status;
    }

    public function collection()
    {
        return TranslateText::getDataForExport($this->search, $this->category, $this->language, $this->status);
    }
}