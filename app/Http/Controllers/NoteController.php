<?php

namespace App\Http\Controllers;
use App\Note;
use App\Http\Requests;
use Request;
use App\src\FileStructure\DirUploadFileStructure;
use App\src\Factory as F;
use Symfony\Component\HttpFoundation\File;
use Illuminate\Container as Container;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

    //*********using container object ( also in seeds(fixture) )***********/
        $check = \App::make('CheckStructureService');
        $check->init();
    //*********using container object ( also in seeds(fixture) )***********/

        $uplName = config('parameters.uplName');

        $dir = new DirUploadFileStructure;

        $params = $dataArray = $dir->getDirContent($uplName, "", $content);
echo "<pre> dataArray =", var_dump($dataArray) ,"</pre>";

        /***********Create a tree structure******************************/
        $factory = new F\GoodsFactory();
            // To create the main node through the factory.
        $root = $factory->createRoot(array('nodeId'=>0, 'nodeName'=>'root'));
            // Gathering wood
        if (!empty($dataArray)) {
            // Sort the array with the data so that the branches was the first.
/***********replace create_function with anonymous function*************************************************************************************************/
            /*

             usort($dataArray, create_function('$a,$b','if ((int)$a["parentId"]===(int)$b["parentId"]) return (int)$a["nodeId"]>(int)$b["nodeId"] ? 1 : -1;
                        return (int)$a["parentId"]>(int)$b["parentId"] ? 1 : -1;'));
            */


/*usort($dataArray, function($a,$b) { if ((int)$a["parentId"]===(int)$b["parentId"]) return (int)$a["nodeId"]>(int)$b["nodeId"] ? 1 : -1;
                                    return (int)$a["parentId"]>(int)$b["parentId"] ? 1 : -1;});*/

            $func = function($a,$b) { if ((int)$a['parentId']===(int)$b['parentId']) return (int)$a['nodeId']>(int)$b['nodeId'] ? 1 : -1;
                                        return (int)$a['parentId']>(int)$b['parentId'] ? 1 : -1;};
            usort($dataArray, $func);

/***********replace create_function with anonymous function*************************************************************************************************/


echo "<pre> Sorted dataArray = ", print_r($dataArray) ,"</pre>";
            foreach ($dataArray as $data) {
                $iterator = $root->getIterator();
dump($iterator);
                $iterator->seek($data['parentId']);
                $parent = $iterator->current();
                $item = $factory->create($data);
                $parent->addChild($item);
            }
        }
        $tree = $root->getDataToPrint();
echo "<pre> tree = ", print_r($tree) ,"</pre>";
        /***********Create a tree structure*****************************/

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

//        $Request = Request::file('uploadfile');
//echo "<pre> Request = ", var_dump($Request) ,"</pre>";

/**********обьявление дополнительных переменных-параметров( "$_" ) через Request для передачи в loadNoteToDir();***********/

        $pathName = Request::file('uploadfile')->getRealPath();
//echo "<pre> pathName = ", var_dump($pathName) ,"</pre>";
        $fileExtension = Request::file('uploadfile')->getClientOriginalExtension();
//echo "<pre> fileExtension = ", var_dump($fileExtension) ,"</pre>";
        $originalFileName = Request::file('uploadfile')->getClientOriginalName();   // $_FILES["uploadfile"]["name"];
//echo "<pre> originalFileName = ", var_dump($originalFileName) ,"</pre>";

/**********обьявление дополнительных переменных-параметров( "$_" ) через Request для передачи в loadNoteToDir();***********/

        $dir = new DirUploadFileStructure;
        $params = $dir->loadNoteToDir($uplName, $uplPath, $countContentFiles, $pathName, $originalFileName,  $fileExtension);
//echo "<pre> params = ", print_r($params) ,"</pre>";
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

        $uplName = config('parameters.uplName');
        $editorContent = Request::input('editor1');

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

