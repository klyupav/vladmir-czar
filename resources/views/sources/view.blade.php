@extends('layouts.app')

@section('title')/Источники@endsection

@section('content')
    <div class="container">
        @if (session('alert-success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('alert-success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <h1>Источники</h1>
            </div>
        </div>

            <div class="row">
                {{--ФИЛЬТР--}}
                <div class="row">
                    <form class="" role="search">
                        <table class="table table-striped">
                            <tr>
                                <td>
                                    <select name="donor_class_name" class="form-control" >
                                        <option value="">Все доноры</option>

                                        @foreach( $donor_list as $donor )
                                            @if( isset($_GET['donor_class_name']) && $_GET['donor_class_name'] == $donor )
                                                <option value="{{ $donor }}" selected>{{ $donor }}</option>
                                            @else
                                                <option value="{{ $donor }}">{{ $donor }}</option>
                                            @endif
                                        @endforeach

                                    </select>
                                </td>
                                <td>
                                    <input value="@if(isset($_GET['source'])){{ $_GET['source'] }}@endif" type="text" name="source" class="form-control" placeholder="Ссылка на источник">
                                </td>
                                <td>
                                    <input value="@if(isset($_GET['name'])){{ $_GET['name'] }}@endif" type="text" name="name" class="form-control" placeholder="Источник">
                                </td>
                                <td>
                                    <select name="parseit" class="form-control" >
                                        <option value="both">Парсинг</option>
                                        <option value="true"@if( isset( $_GET['parseit'] ) && $_GET['parseit'] === 'true' ){{ 'selected' }}@endif>Галка стоит</option>
                                        <option value="false"@if( isset( $_GET['parseit'] ) && $_GET['parseit'] === 'false' ){{ 'selected' }}@endif>Галки нет</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <button type="submit" class="btn btn-default">Поиск</button>
                    </form>
                </div>
                {{--ФИЛЬТР--}}
            </div>
            <hr>
            <div class="row">
                <table class="table">
                    <tr>
                        <th>Донор</th>
                        <th>Источник</th>
                        <th>парсинг</th>
                        <th></th>
                    </tr>
                    @foreach($sources as $source)
                        <tr id="{{$source->hash}}">
                            <td>{{$source->donor_class_name}}</td>
                            <td>
                                <a href="{{urldecode($source->source)}}" target="_blank">
                                    <img width="150px" src="{{ $source->image }}"><br>
                                    {{$source->name}}<br>
                                </a>
                            </td>
                            <td>
                                <input onclick="eventParseitChecked(this)" type="checkbox" name="parseit" @if( $source->parseit ){{ 'checked' }}@endif>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <p class="text-right"> {{$sources->lastItem()}} из {{$sources->total()}}</p>
                {{$sources->appends(request()->input())->links()}}
            </div>
    </div>

@endsection

@push('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

        function eventParseitChecked(elem)
        {
            var td = $(elem).parent();
            var tr = td.parent();
            var hash = tr.prop('id');

            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'hash' : hash,
                'parseit' : $( elem ).prop( "checked" ),
            };
            console.log();
            $.ajax({
                method : 'POST',
                url : '/parseit-off-on',
                data : data,
                dataType : 'json'
            }).done(function( data ) {
                if ( ! data.moderation )
                {
                    $('#'+hash).removeClass('alert-danger');
                }
                else
                {
                    $('#'+hash).addClass('alert-danger');
                }
                console.log( data );
            }).error(function( jqXHR) {
                if (jqXHR.status == 500) {
                    console.log(jqXHR.responseJSON.error);
                }
                else
                {
                    console.log(jqXHR.responseText);
                }
            });
        }
    </script>
@endpush
