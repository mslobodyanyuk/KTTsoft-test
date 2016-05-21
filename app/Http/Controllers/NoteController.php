<?php

namespace App\Http\Controllers;
use App\Note;
use App\Http\Requests;
use Request;
use App\Http\Dirs;
use Symfony\Component\HttpFoundation\File;
class NoteController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dir = new Dirs;
        $params = $dir->GetDirContent("upl", $offs="", $content);

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
        $dir = new Dirs;
        $params = $dir->loadNoteToDir("upl");
        //   echo "<pre>", var_dump($params), "</pre>";

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
        return view('notes.show',compact('note'));
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
        return view('notes.edit',compact('note'));
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
        $contents = $_POST['editor1'];
        \File::put('upl/'.$note->directory_name.'/'.$note->name, $contents);
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
        \File::delete('upl/'.$note->directory_name.'/'.$note->name);

        $dir = new Dirs;

        $dirPath = 'upl/'.$note->directory_name;
        if (is_dir($dirPath)){//if (file_exists($dirpath)){
           if ($dir->count_files($dirPath) == 0) {
                rmdir($dirPath);
            }
        }
        Note::find($id)->delete();
        return redirect('notes');
    }
}

