<?php

namespace App\Http\Controllers;
use App\Note;
use App\Http\Requests;
use Request;

use App\src\FileStructure\DirUploadFileStructure;
use App\src\Services\CheckStructureService;
use App\src\Path\UploadPath;

use App\src\Factory as F;

use Symfony\Component\HttpFoundation\File;
class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $uplPath = new UploadPath();
        $check = new CheckStructureService($uplPath);

            //$check = $this->app->make('CheckStructureService');  //!!!

        $check->init();

        $uplName = config('parameters.uplName');

        $dir = new DirUploadFileStructure;

        $dataArray = $dir->getDirContent($uplName, $content);


                echo "<pre>", var_dump($dataArray) ,"</pre>";

        /***********Create a tree structure******************************/
        $factory = new F\GoodsFactory();
            // Sort the array with the data so that the root was the first branch.
       /* usort($dataArray, create_function('$a,$b','if ($a["parentFolderName"]===$b["parentFolderName"]) return 0;
                 return $a["parentFolderName"]>$b["parentFolderName"] ? 1 : -1;'));
       /* usort($dataArray, create_function('$a,$b','if ($a["type"]===$b["type"]) return 0;
                 return $a["type"] > $b["type"] ? 1 : -1;'));*/

        /*usort($dataArray, create_function('$a,$b','if ($a["parentId"]===$b["parentId"]) return 0;
	     return (int)$a["parentId"] > (int)$b["parentId"] ? 1 : -1;'));
        */
       /* usort($dataArray, create_function('$a,$b','if ((int)$a["type"]===(int)$b["type"]) return 0;
	     return (int)$a["type"]<(int)$b["type"] ? 1 : -1;'));*/

                echo "<pre>", var_dump($dataArray) ,"</pre>";
            // To create the main node through the factory.
        $root = $factory->createRoot(array('nodeId'=>0, 'nodeName'=>'root'));
            // Gathering wood
        if (!empty($dataArray)) {
            foreach ($dataArray as $data) {
                $iterator = $root->getIterator();
                $iterator->seek($data['parentId']);
                //$iterator->seek($data['parentFolderName']);
                $parent = $iterator->current();
                $item = $factory->create($data);
                $parent->addChild($item);
            }
        }
         $tree = $root->getDataToPrint();
                echo "<pre> tree = ", print_r($tree) ,"</pre>";
        /***********Create a tree structure*****************************/


        $params = $dataArray;



        $notes=Note::all();

       return view('notes.index', compact('params','notes'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $uplName = config('parameters.uplName');
        $uplPath = config('parameters.uplPath');
        $countContentFiles = config('parameters.countContentFiles');

        $dir = new DirUploadFileStructure;
        $params = $dir->loadNoteToDir($uplName, $uplPath, $countContentFiles );

        if ( ( $params[0] !== null ) and ( $params[1] !== null ) ) {
            $directory_name = $params[0];
            $name = $params[1];
            Note::create(['directory_name' => $directory_name, 'name' => $name]);
            return redirect('notes');
        } else {
            return view('notes.nottxt', compact('params'));
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $note=Note::find($id);

        $uplName = config('parameters.uplName');
        $contents = \File::get($uplName.'/'.$note->directory_name.'/'.$note->name);

        return view('notes.show', compact('note','contents'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $note=Note::find($id);

        $uplName = config('parameters.uplName');
        $contents = \File::get($uplName.'/'.$note->directory_name.'/'.$note->name);

        return view('notes.edit',compact('note','contents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $note=Note::find($id);

        $editorContent = config('parameters.editorContent');
        $uplName = config('parameters.uplName');
        \File::put($uplName.'/'.$note->directory_name.'/'.$note->name, $editorContent);
        return redirect('notes');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $note=Note::find($id);
        $uplName = config('parameters.uplName');
        \File::delete($uplName.'/'.$note->directory_name.'/'.$note->name);

        $dir = new DirUploadFileStructure;
        $dirPath = $uplName.'/'.$note->directory_name;

        if (is_dir($dirPath) && ($dir->countFiles($dirPath) == 0)){
            rmdir($dirPath);
        }

        Note::find($id)->delete();
        return redirect('notes');
    }
}

