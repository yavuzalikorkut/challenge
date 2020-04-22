<?php

namespace App\Jobs;

use App\Category;
use Aspera\Spreadsheet\XLSX\Reader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProcessCats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
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
    }
}
