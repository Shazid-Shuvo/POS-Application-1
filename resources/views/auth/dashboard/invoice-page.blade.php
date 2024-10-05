@extends('layout.side-nav')
@section('content')
    @include('components.invoice.invoice-list')
    @include('components.invoice.invoice-details')
    @include('components.invoice.invoice-delete')
@endsection
