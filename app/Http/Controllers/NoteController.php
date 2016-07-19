<?php

namespace App\Http\Controllers;
use App\Note;
use App\Http\Requests;
use Request;

use App\src\FileStructure\DirUploadFileStructure;
use App\src\Services\CheckStructureService;
use App\src\Path\UploadPath;

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
        $params = $dir->getDirContent($uplName, $offs="", $content);

        $tagMarkers = ['brake','boldOpen', 'boldClose', 'ahrefOpen', 'closeBracket', 'ahrefClose', 'spanOpen',    'spanClose', 'aCloseDownload'];
        $partTags = [ '<BR>'  ,'<B>',        '</B>',     '<a href=',  '>',            '</a>',      '<span path=', '</span>',    'download>'];
        $params = str_replace( $tagMarkers, $partTags, $params );

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
        //$contents = $_POST['editor1'];
        //$editorContent = config('parameters.editorContent');
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

