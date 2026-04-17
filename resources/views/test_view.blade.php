@extends("admin.layout")
@section("content")
{{ $dataTable->table() }}
@endsection
@section("scripts")
{{ $dataTable->scripts() }}
@endsection
