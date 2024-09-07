@extends('layouts.AdminLTE.siteindex')
@section('content')
{{$pageValue->title}}
{!! $pageValue->content !!}
@endsection