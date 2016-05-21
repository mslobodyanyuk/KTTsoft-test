@extends('layout.template')
@section('content')
    <head>
        <script type="text/javascript" src="/js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    </head>
    <h1>Update File</h1>
    {!! Form::model($note,['method' => 'PATCH','route'=>['notes.update',$note->id]]) !!}
    <div class="form-group">
        <div class="col-sm-7">
            {!! Form::label('Name', 'Name:') !!}
            {!! Form::text('name',null,['class'=>'form-control', 'readonly' => 'true']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-7">
            {!! Form::label('Directory id', 'Directory name:') !!}
            {!! Form::text('directory_name', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="editor1" class="col-sm-7 control-label">File contents:</label>
        <div class="col-sm-7">
           <!-- <textarea name="editor1" id="editor1" class="col-sm-12" > -->
            <textarea class="form-control" rows="5"  name="editor1" cols="50" id="editor1">
                {{\File::get('upl/'.$note->directory_name.'/'.$note->name)}}
            </textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-7">
            {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@stop