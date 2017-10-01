@extends('layouts.app')

@section('title')/Мероприятия@endsection

@section('content')
    <div class="container">
        @if (session('alert-success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('alert-success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <h1>Сопоставление Мероприятий</h1>
            </div>
        </div>

            <div class="row">
            {{--ФИЛЬТР--}}
            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
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
                    <input value="@if(isset($_GET['event'])){{ $_GET['event'] }}@endif" type="text" name="event" class="form-control" placeholder="Мероприятие у донора">
                    <button type="submit" class="btn btn-default">Поиск</button>
                </div>
            </form>
            {{--ФИЛЬТР--}}
            </div>
            <hr>
            <div class="row">
                <table class="table table-striped">
                    <tr>
                        <th><a class="btn" data-order-by="donor_class_name" data-order="@if( isset($_GET['orderBy']) && $_GET['orderBy'] == 'donor_class_name' && isset($_GET['order']) ){{ $_GET['order'] == 'asc' ? 'asc' : 'desc' }}@endif">Донор <span class="glyphicon glyphicon-sort"></span></a></th>
                        <th><a class="btn" data-order-by="event" data-order="@if( isset($_GET['orderBy']) && $_GET['orderBy'] == 'event' && isset($_GET['order']) ){{ $_GET['order'] == 'asc' ? 'asc' : 'desc' }}@endif">Мероприятие донора  <span class="glyphicon glyphicon-sort"></span></a></th>
                        <th><a class="btn" data-order-by="kassirclub_event" data-order="@if( isset($_GET['orderBy']) && $_GET['orderBy'] == 'kassirclub_event' && isset($_GET['order']) ){{ $_GET['order'] == 'asc' ? 'asc' : 'desc' }}@endif">Мероприятие KassirClub <span class="glyphicon glyphicon-sort"></span></a></th>
                    </tr>
                    @foreach($events as $event)
                        <tr>
                            <td>{{$event->donor_class_name}}</td>
                            <td>{{$event->event}}</td>
                            <td>
                                <input type="hidden" readonly class="form-control " name="event_hash" value="{{$event->event_hash}}" />
                                <input type="hidden" readonly class="form-control " name="kassirclub_event_id" value="{{$event->kassirclub_event_id}}" />
                                <input type="text" class="form-control " name="kassirclub_event" value="{{$event->kassirclubEvent()->event}}" />
                                <input readonly type="text" class="form-control alert-success" value="" style="display: none" />
                                <label class="alert-danger" style="display: none"></label>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <p class="text-right"> {{$events->lastItem()}} из {{$events->total()}}</p>
                {{$events->links()}}
            </div>
    </div>

@endsection

@push('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $("input[name=kassirclub_event]").on('keypress', function (e) {
                if (e.keyCode == 13)
                {
                    var td = $(this).parent();
                    var alert_error = td.find('label.alert-danger');
                    var alert_info = td.find('input.alert-success');
                    var input = $(this);
                    var data = {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'event_hash' : td.find('input[name=event_hash]').val(),
                        'kassirclub_event_id' : td.find('input[name=kassirclub_event_id]').val(),
                        'kassirclub_event' : $(this).val(),
                    };
                    alert_error.hide();
                    alert_info.val('Ожидайте...')
                    alert_info.show();
                    input.hide();
                    $.ajax({
                        method : 'POST',
                        url : '/events/associate',
                        data : data,
                        dataType : 'json'
                    }).done(function( data ) {
                        alert_info.hide();
                        console.log( data );
                        input.show();
                    }).error(function( jqXHR) {
                        alert_info.hide();
                        input.show();
                        if (jqXHR.status == 500) {
                            alert_error.html(jqXHR.responseJSON.error);
                        }
                        else
                        {
                            alert_error.html(jqXHR.responseText);
                        }
                        alert_error.slideDown();
                    });
                }
            });

            $('th a.btn').each(function () {
                if ( $(this).data('order') == 'asc' )
                {
                    $(this).find('span').attr('class', 'glyphicon glyphicon-sort-by-alphabet');
                }
                else if ( $(this).data('order') == 'desc' )
                {
                    $(this).find('span').attr('class', 'glyphicon glyphicon-sort-by-alphabet-alt');
                }
                else
                {
                    $(this).find('span').attr('class', 'glyphicon glyphicon-sort');
                }
            });

            $("th a.btn").on('click', function () {
                var orderBy = $(this).data('order-by');
                var order = 'asc';

                $(this).find('span').attr('class', 'glyphicon glyphicon-sort-by-alphabet');
                if ( $(this).data('order') == 'asc')
                {
                    order = 'desc';
                    $(this).find('span').attr('class', 'glyphicon glyphicon-sort-by-alphabet-alt');
                }
                $(this).data('order', order);
//                var param = {orderBy : orderBy+':'+order};


                var params={};
                window.location.search
                    .replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str,key,value) {
                            params[key] = value;
                        }
                    );
                params['orderBy'] = orderBy;
                params['order'] = order;
                document.location = document.location.href.replace(/\?.*$/, "") + "?" + $.param(params);
                console.log(document.location);
            });
        });
    </script>
    <script>
        $( function() {
            $.widget( "custom.catcomplete", $.ui.autocomplete, {
                _create: function() {
                    this._super();
                    this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
                },
                _renderMenu: function( ul, items ) {
                    var that = this;
                    $.each( items, function( index, item ) {
                        var li;
                        li = that._renderItemData( ul, item );
                    });
                }
            });
            var data = [
                    @foreach($kassirclub_events as $kassirclub_event)
                { label: '{{ $kassirclub_event->event }}'},
                @endforeach
            ];

            $( "input[name=kassirclub_event]" ).catcomplete({
                delay: 0,
                source: data
            });
        } );
    </script>
@endpush
