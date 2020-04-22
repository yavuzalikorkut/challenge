<?php

namespace App\Http\Controllers;

use App\Category;
use App\Jobs\ProcessCats;
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
        $this->dispatch(new ProcessCats());

        return response()->json([
            'message' => 'Bilgileriniz Mernis kontrolünden geçirilerek tarafınıza e-posta gönderilecektir.'
        ], 200);
    }

}
