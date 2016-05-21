@extends('layout.template')
@section('content')
    <h1>Create File</h1>
    <form action="/notes" method="post" enctype="multipart/form-data">
        <div class="form-group">
            {!! Form::label('Upload File:') !!}
            <input type="file" name="uploadfile" class="btn btn-primary">
        </div>
        <div class="form-group">
		    {!! Form::submit('Save', ['class' => 'btn btn-primary ']) !!}
        </div>
    </form>
  @stop