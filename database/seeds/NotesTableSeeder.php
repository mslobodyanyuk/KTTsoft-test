<?php

use Illuminate\Database\Seeder;
use Symfony\Component\HttpFoundation\File;
use App\src\Services\CheckStructureService;
use App\src\Path\UploadPath;

class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $uplPath = new UploadPath();

        $check = new CheckStructureService($uplPath);
        $check->init();

        if (mkdir( $uplPath->getPath().'/1' , 0777 )) {
            \File::put($uplPath->getPath().'/1/TEST1.txt', 'content TEST1.txt');
            \File::put($uplPath->getPath().'/1/TEST2.txt', 'content TEST2.txt');
        }

        if (mkdir( $uplPath->getPath().'/2' , 0777 )) {
            \File::put($uplPath->getPath().'/2/TEST1.txt', 'content TEST1.txt');
            \File::put($uplPath->getPath().'/2/TEST2.txt', 'content TEST2.txt');
        }

        if (mkdir( $uplPath->getPath().'/3' , 0777 )) {
            \File::put($uplPath->getPath().'/3/TEST1.txt', 'content TEST1.txt');
            \File::put($uplPath->getPath().'/3/TEST2.txt', 'content TEST2.txt');
        }

        DB::table('notes')->insert(['name' => 'TEST1.txt', 'directory_name' => '1']);
        DB::table('notes')->insert(['name' => 'TEST2.txt', 'directory_name' => '1']);
        DB::table('notes')->insert(['name' => 'TEST1.txt', 'directory_name' => '2']);
        DB::table('notes')->insert(['name' => 'TEST2.txt', 'directory_name' => '2']);
        DB::table('notes')->insert(['name' => 'TEST1.txt', 'directory_name' => '3']);
        DB::table('notes')->insert(['name' => 'TEST2.txt', 'directory_name' => '3']);
    }

}
