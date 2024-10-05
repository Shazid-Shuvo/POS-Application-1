@extends('layout.side-nav')
@section('content')
    @include('components.product.list-product')
    @include('components.product.create-product')
    @include('components.product.delete-product')
    @include('components.product.update-product')
@endsection
