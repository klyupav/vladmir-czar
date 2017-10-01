@extends('layouts.app')

@section('title')/Мероприятия к заполнению@endsection

@section('content')
    <div class="container">
        @if (session('alert-success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('alert-success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <h1>Мероприятия к заполнению</h1>
            </div>
        </div>
            <div class="row">
                {{--ФИЛЬТР--}}
                <table class="table table-striped">
                    <tr>
                        <td>
                            <div class="checkbox">
                                <label class="alert-danger">
                                    <input type="checkbox" name="moderation" > отмеченые
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
                {{--ФИЛЬТР--}}
            </div>
            <div class="row">
                <table class="table">
                    <tr>
                        <th></th>
                        <th>Название мероприятия</th>
                        <th>Ссылка на донора</th>
                        <th>id мероприятия в кассирклабе</th>
                        <th>id площадки в кассирклабе</th>
                        <th>дата проведения</th>
                    </tr>
                    @foreach($sources as $source)
                        <tr>
                            <td><input type="checkbox" name="ready" data-kassirclub_event_id="{{$source->kassirclub_event_id}}" data-kassirclub_location_id="{{$source->kassirclub_location_id}}" data-date_event="{{$source->date_event}}" @if(App\Models\ReadyEvent::isChecked($source->kassirclub_event_id, $source->kassirclub_location_id, $source->date_event)): checked @endif  /></td>
                            <td>{{$source->event}}</td>
                            <td>
                                <a href="{{$source->source}}" target="_blank">
                                    источник
                                </a>
                            </td>
                            <td>{{$source->kassirclub_event_id}}</td>
                            <td>{{$source->kassirclub_location_id}}</td>
                            <td>{{$source->date_event}}</td>
                        </tr>
                    @endforeach
                </table>
                {{--<p class="text-right"> {{$sources->lastItem()}} из {{$sources->total()}}</p>--}}
                {{--{{$sources->appends(request()->input())->links()}}--}}
            </div>
    </div>

@endsection

@push('css')
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {

            $("input[name=ready]").on('click', function () {
//                alert($(this).data('kassirclub_event_id'));
                var kassirclub_event_id = $(this).data('kassirclub_event_id');
                var kassirclub_location_id = $(this).data('kassirclub_location_id');
                var date_event = $(this).data('date_event');
                var checked = $(this).prop('checked');
                var data = {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'kassirclub_event_id' : kassirclub_event_id,
                    'kassirclub_location_id' : kassirclub_location_id,
                    'date_event' : date_event,
                    'checked' : checked,
                };
                $.ajax({
                    method : 'POST',
                    url : '/sources-ready/checked',
                    data : data,
                    dataType : 'json'
                }).done(function( data ) {
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
            });

            $("input[name=moderation]").on('click', function () {
                var checked = $(this).prop('checked');
                $('input[name=ready]').each(function () {
                    console.log(this);
                    if ($(this).prop('checked') == checked)
                    {
                        $(this).parent().parent().show();
                    }
                    else
                    {
                        $(this).parent().parent().hide();
                    }
                })
            });

        });
    </script>
@endpush
