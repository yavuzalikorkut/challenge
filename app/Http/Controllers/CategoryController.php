<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Aspera\Spreadsheet\XLSX\Reader;
use Aspera\Spreadsheet\XLSX\SharedStringsConfiguration;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fileList = Storage::allFiles('categories');

        Storage::disk('local')->put('temp.xlsx', Storage::get($fileList[0]));

        $reader = new Reader();
        $reader->open(storage_path('app/temp.xlsx'));

        foreach ($reader as $row) {
            dd($row);
        }

        Storage::disk('local')->delete('temp.xlsx');

    }

}
