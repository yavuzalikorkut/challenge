<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        $toName = env('MAIL_TO_NAME');
        $toEmail = env('MAIL_TO');


        $fileList = Storage::allFiles('categories');

        Storage::disk('local')->put('temp.xlsx', Storage::get($fileList[0]));

        $reader = new Reader();
        $reader->open(storage_path('app/temp.xlsx'));

        $reader->next(); // ignore columns

        while (($row = $reader->next()) != false) {
            for ($i = 0; $i < count($row); ++$i) {
                if ($row[$i] == "") {
                    break;
                } else {
                    $category = Category::firstOrCreate(['name' => $row[$i]]);
                    if ($i != 0) {
                        $parentCategory = Category::where(['name' => $row[$i-1]])->first();
                        $category->update(['parent_id' => $parentCategory->id]);
                    }
                }
            }
        }

        Storage::disk('local')->delete('temp.xlsx');

        Mail::send([], [], function($message) use ($toName, $toEmail) {
            $message->to($toEmail, $toName)->subject("Kategori Bilgilendirmesi");
            $message->from($toEmail,"challenge email");
        });


        return "Success";
    }

}
