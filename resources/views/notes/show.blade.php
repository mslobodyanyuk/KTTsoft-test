@extends('layout/template')
@section('content')
    <head>
        <script type="text/javascript" src="/js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    </head>

    <h1>Note Show</h1>

    <form class="form-horizontal">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="name" placeholder={{$note->name}} readonly>
            </div>
        </div>
        <div class="form-group">
            <label for="directory_id" class="col-sm-2 control-label">Directory name</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="directory_id" placeholder={{$note->directory_name}} readonly>
            </div>
        </div>
        <div class="form-group">
            <label for="editor1" class="col-sm-2 control-label">File contents:</label>
            <div class="col-sm-5"><!--\File::get('upl/'.$note->directory_id.'/'.$note->name)}}-->
                <textarea class="form-control" rows="5" placeholder="Note text:" name="editor1" cols="50" id="editor1" readonly></textarea>
            </div>
        </div>

        <script type="text/javascript">
            <?php $path = '/upl/'.$note->directory_name.'/'.$note->name;?>
            var content = "<?php echo $path;?>";
            $(document).ready(function() {
                  $('#editor1').load(content);
            });
        </script>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-5">
                <a href="{{ url('notes')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </form>
@stop