@extends('layout.template')
@section('content')

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
            {!! Form::label('directoryName', 'Directory name:') !!}
            {!! Form::text('directory_name', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-7">
            {!! Form::label('fileContents', 'File contents:') !!}
            {!! Form::textarea('editor1', $contents, ['class' => 'form-control', 'size' => '50x5']) !!}
        </div>
  </div>
  <div class="form-group">
      <div class="col-sm-7">
          {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
      </div>
  </div>
  {!! Form::close() !!}
@stop