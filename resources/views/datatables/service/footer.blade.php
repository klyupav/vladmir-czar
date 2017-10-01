@extends('layouts.app')

@section('content')
    <div class="container">
        {!! $dataTable->table([], true) !!}
    </div>
@endsection

@push('scripts')
<link rel="stylesheet" href="/js/buttons/css/buttons.dataTables.css">
<script src="/js/buttons/js/dataTables.buttons.js"></script>
<script src="/vendor/datatables/buttons.server-side.js"></script>
{!! $dataTable->scripts() !!}
@endpush


