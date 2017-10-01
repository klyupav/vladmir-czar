@extends('layouts.app')

@section('title')/Списки прокси и user-agent @endsection

@section('content')
    <div class="container">
        @if (session('alert-success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('alert-success') }}
            </div>
        @endif
        <div class="row">
        </div>
            <div class="row">

                <div class="col-md-12">
                    <h1>Список прокси</h1>
                </div>
                <form class="" action="{{route('proxy.update', 1)}}" method="post">
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-3">
                        <textarea class="form-control" name="list" rows="20">{{ $proxy_list }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-success" value="Сохранить">
                    </div>
                </form>

                <div class="col-md-12">
                    <h1>Список юзер-агентов</h1>
                </div>
                <form class="" action="{{route('useragent.update', 1)}}" method="post">
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-3">
                        <textarea class="form-control" name="list" rows="20">{{ $useragent_list }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-success" value="Сохранить">
                    </div>
                </form>

            </div>
    </div>
@endsection
