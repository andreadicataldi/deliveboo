@extends('layouts.guest')

@section('content')
{{-- <restaurant-component :restaurant='@json($ristorante)' /> --}}
<rest :rest='@json($ristorante)' route="{{ route('checkout') }}" />
@endsection