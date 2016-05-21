@extends('layout/template')
@section('content')
    <form class="form-horizontal">
        <h1>Message:</h1>
        <div class="text-center">
            <h1 class='text-center'><i style='color:orange'>{!!$params['message']!!}</i></h1>
        </div>
    </form>
@stop