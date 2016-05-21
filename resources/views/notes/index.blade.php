
@extends('layout/template')

@section('content')
    <head>
        <script type="text/javascript" src="/js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    </head>

    <div class="container">

        <div class="form-group">
            <label><h1>Welcome to test program!!!</h1></label><br />
            <label><h1>Tree structure content folder "upl/:"</h1></label>
        </div>
        @if (! empty($params) && is_array($params))
        @foreach ($params as $param)
               {!!$param!!}
        @endforeach
        @endif

        <div class="form-group">
            <label for="editor1"><h1>File contents:</h1></label>
            <textarea class="form-control" rows="5" placeholder="Note text:" name="editor1" cols="50" id="editor1" readonly></textarea>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $("span").click(function() {
                var path = $(this).attr("path");
                $.get( path, function(data) {
                            $("#editor1").val(data);
                        }, 'text'
                );
            });
        });
    </script>




 <h1>Table files:</h1>
 <a href="{{url('/notes/create')}}" class="btn btn-success">Create File</a>
 <hr>
 <table class="table table-striped table-bordered table-hover">
     <thead>
        <tr class="bg-info">
            <th>Directory name</th>
            <th>Name</th>
            <th colspan="3">Actions</th>
        </tr>
     </thead>
     <tbody>

     @foreach ($notes as $note)
         <tr>
             <td>{{ $note->directory_name }}</td>
             <td>{{ $note->name }}</td>

             <td><a href="{{url('notes',$note->id)}}" class="btn btn-primary">Read</a></td>
             <td><a href="{{route('notes.edit',$note->id)}}" class="btn btn-warning">Update</a></td>
             <td>
                {!! Form::open(['method' => 'DELETE', 'route'=>['notes.destroy', $note->id]]) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
             </td>
         </tr>
     @endforeach
     </tbody>
 </table>
@endsection