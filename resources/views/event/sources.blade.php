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
                <h1>Источники билетов</h1>
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
                                    <input value="@if(isset($_GET['event'])){{ $_GET['event'] }}@endif" type="text" name="event" class="form-control" placeholder="Мепроприятие">
                                </td>
                                <td>
                                    <input value="@if(isset($_GET['location'])){{ $_GET['location'] }}@endif" type="text" name="location" class="form-control" placeholder="Площадка">
                                </td>
                                <td>
                                    <select name="parseit" class="form-control" >
                                        <option value="both">Парсинг</option>
                                        <option value="true"@if( isset( $_GET['parseit'] ) && $_GET['parseit'] === 'true' ){{ 'selected' }}@endif>Галка стоит</option>
                                        <option value="false"@if( isset( $_GET['parseit'] ) && $_GET['parseit'] === 'false' ){{ 'selected' }}@endif>Галки нет</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="checkbox">
                                        <label class="alert-danger">
                                            <input type="checkbox" name="moderation" @if( isset( $_GET['moderation'] ) ){{ 'checked' }}@endif> требует внимания
                                        </label>
                                    </div>
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
                        <th>Мероприятие</th>
                        <th>Сопоставление с меропритием из Кассирклаба</th>
                        <th>Сопоставление с площадкой из Кассирклаба</th>
                        <th>Сопоставление секторов из Кассирклаба</th>
                        <th>Билетов в базе</th>
                        <th>парсинг</th>
                        <th></th>
                    </tr>
                    @foreach($sources as $source)
                        <?php
//                            $source->needModeration();
                            $source->update(['moderation' => $source->needModeration()]);
                        ?>
                        <tr id="{{$source->event_hash}}" @if( $source->moderation ) class="alert-danger"@endif>
                            <td>{{$source->donor_class_name}}</td>
                            <td>
                                <a href="{{urldecode($source->source)}}" target="_blank">
                                    <img width="150px" src="{{ $source->image }}"><br>
                                    {{$source->event}}<br>
                                    {{$source->location}}<br>
                                </a>
                            </td>
                            <td class="td" align="center">
                                <input type="hidden" readonly class="form-control " name="event_hash" value="{{$source->event_hash}}" />
                                <input type="hidden" readonly class="form-control " name="kassirclub_event_id" value="{{$source->kassirclub_event_id}}" />
                                <div class="input-group">
                                    <input title="{{$source->kassirclubEvent()->event}}" oninput="addButton(this)" onfocus="$(this).trigger(jQuery.Event('keydown'));inputFocus(this);" type="text" class="form-control " name="kassirclub_event" value="{{$source->kassirclubEvent()->event}}" />
                                    <span class="input-group-btn">
                                        <button onclick="associateEvent($(this).parent().parent().find('input[name=kassirclub_event]'))" style="display: none" type="button" class="btn btn-success">ok</button>
                                    </span>
                                </div>
                                <a target="_blank" href="/kassir-club-api/get-events-info">upd</a>
                                <input readonly type="text" class="form-control alert-success" value="" style="display: none" />
                                <label class="alert-danger" style="display: none"></label>
                            </td>
                            <td align="center">
                                <input type="hidden" readonly class="form-control " name="location" value="{{$source->location}}" />
                                <input type="hidden" readonly class="form-control " name="kassirclub_location_id" value="{{$source->kassirclub_location_id}}" />
                                <div class="input-group">
                                    {{--<input oninput="addButton(this)" onfocus="$(this).trigger(jQuery.Event('keydown'));inputFocus(this);" type="text" class="form-control " name="kassirclub_event" value="{{$source->kassirclubEvent()->event}}" />--}}
                                    <input title="{{$source->kassirclubLocation()->location}}" oninput="addButton(this)" onfocus="$(this).trigger(jQuery.Event('keydown'));inputFocus(this);" type="text" @if( empty($source->kassirclub_event_id)){{ 'readonly' }}@endif class="form-control " name="kassirclub_location" value="{{$source->kassirclubLocation()->location}}" />
                                    <span class="input-group-btn">
                                        <button onclick="associateLocation($(this).parent().parent().find('input[name=kassirclub_location]'))" style="display: none" type="button" class="btn btn-success">ok</button>
                                    </span>
                                </div>
                                <a target="_blank" href="/kassir-club-api/get-events-info">upd</a>
                                <input readonly type="text" class="form-control alert-success" value="" style="display: none" />
                                <label class="alert-danger" style="display: none"></label>
                            </td>
                            <td class="sectors-table" data-event-hash="{{ $source->event_hash }}" data-load-table="@if($source->kassirclub_event_id && $source->kassirclub_location_id){{ 'true' }}@endif">

                            </td>
                            <td>
                                {{ $source->tickets_found }}
                            </td>
                            <td>
                                <input onclick="eventParseitChecked(this)" type="checkbox" name="parseit" @if( $source->parseit ){{ 'checked' }}@endif>
                            </td>
                            <td>
                                @if($source->kassirclub_event_id && $source->kassirclub_location_id)
                                    <a target="_blank" href="?kassirclub_event_id={{ $source->kassirclub_event_id }}&kassirclub_location_id={{ $source->kassirclub_location_id }}">все похожие мероприятия</a>
                                @endif
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

        function addButton(e) {
            $(e).parent().find('button').show();
        }

        var timerId;

        function inputFocus(e)
        {
            var lastValue = $(e).val();
            timerId = setInterval(function() {
                if ($(e).val() != lastValue) {
                    addButton(e);
                    lastValue = $(e).val();
                }
            }, 20);
        }

        function associateEvent(e)
        {
            var td = $(e).parent().parent();
            td.find('button').hide();
            var tr = td.parent();
            var alert_error = td.find('label.alert-danger');
            var alert_info = td.find('input.alert-success');
            var input = $(e);
            var event_hash = tr.find('input[name=event_hash]').val();
            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'event_hash' : event_hash,
                'kassirclub_event_id' : td.find('input[name=kassirclub_event_id]').val(),
                'kassirclub_location_id' : tr.find('input[name=kassirclub_location_id]').val(),
                'kassirclub_event' : $(e).val(),
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
                tr.find('input[name=kassirclub_event_id]').val(data.kassirclub_event_id);
                if( data.event )
                {
                    $(e).val(data.event)
                }
                if ( ! data.moderation )
                {
                    $('#'+event_hash).removeClass('alert-danger');
//                        console.log(tr.parent('tr'));
                }
                console.log( data );
                input.show();
                tr.find('input[name=kassirclub_location]').attr('readonly', false);
            }).error(function( jqXHR) {
                alert_info.hide();
                input.show();
                if (jqXHR.status == 500)
                {
                    if ( jqXHR.responseJSON.deleted )
                    {
                        $('#'+event_hash).addClass('alert-danger');
                        tr.find('td.sectors-table').html('');
                        tr.find('input[name=kassirclub_event]').val('');
                        tr.find('input[name=kassirclub_location]').val('');
                        tr.find('input[name=kassirclub_location]').attr('readonly', true);
                    }
                    alert_error.html(jqXHR.responseJSON.error);
                }
                else
                {
                    alert_error.html(jqXHR.responseText);
                }
                alert_error.slideDown();
            });
            td.find('button').hide();
        }

        function associateLocation(e)
        {
            var td = $(e).parent().parent();
            td.find('button').hide();
            var tr = td.parent();
            var alert_error = td.find('label.alert-danger');
            var alert_info = td.find('input.alert-success');
            var input = $(e);
            var event_hash = tr.find('input[name=event_hash]').val();
            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'location' : td.find('input[name=location]').val(),
                'event_hash' : event_hash,
                'kassirclub_location_id' : td.find('input[name=kassirclub_location_id]').val(),
                'kassirclub_location' : $(e).val(),
            };
            alert_error.hide();
            alert_info.val('Ожидайте...')
            alert_info.show();
            input.hide();
            $.ajax({
                method : 'POST',
                url : '/locations/associate',
                data : data,
                dataType : 'json'
            }).done(function( data ) {
                alert_info.hide();
                tr.find('input[name=kassirclub_location_id]').val(data.kassirclub_location_id);
                if( data.location )
                {
                    $(e).val(data.location)
                }
                if ( ! data.moderation )
                {
                    $('#'+event_hash).removeClass('alert-danger');
//                        console.log(tr.parent('tr'));
                }
                console.log( data );
                input.show();
                loadLocationSectorsTable(tr.find('td.sectors-table'), tr.find('input[name=event_hash]').val());
            }).error(function( jqXHR) {
                alert_info.hide();
                input.show();
                if (jqXHR.status == 500) {
                    if ( jqXHR.responseJSON.deleted )
                    {
                        $('#'+event_hash).addClass('alert-danger');
                        tr.find('td.sectors-table').html('');
                        tr.find('input[name=kassirclub_location]').val('');
                    }
                    alert_error.html(jqXHR.responseJSON.error);
                }
                else
                {
                    alert_error.html(jqXHR.responseText);
                }
                alert_error.slideDown();
            });
            td.find('button').hide();
        }

        function associateSector(e)
        {
            var td = $(e).parent().parent();
            td.find('button').hide();
            var tr = td.parent();
            var alert_error = td.find('label.alert-danger');
            var alert_info = td.find('input.alert-success');
            var input = $(e);
            var event_hash = tr.find('input[name=event_hash]').val();
            alert_error.hide();
            alert_info.val('Ожидайте...')
            alert_info.show();
            input.hide();
            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'sector' : td.find('input[name=sector]').val(),
                'event_hash' : event_hash,
                'kassirclub_location_id' : tr.find('input[name=kassirclub_location_id]').val(),
                'kassirclub_sector_id' : tr.find('input[name=kassirclub_sector_id]').val(),
                'kassirclub_sector' : $(e).val(),
            };
//                console.log(data);
            $.ajax({
                method : 'POST',
                url : '/sectors/associate',
                data : data,
                dataType : 'json'
            }).done(function( data ) {
                alert_info.hide();
                tr.find('input[name=kassirclub_sector_id]').val(data.kassirclub_sector_id);
                if ( ! data.moderation )
                {
                    $('#'+event_hash).removeClass('alert-danger');
                }
                else
                {
                    $('#'+event_hash).addClass('alert-danger');
                }
                console.log( data );
                input.show();
            }).error(function( jqXHR) {
                alert_info.hide();
                input.show();
                if (jqXHR.status == 500) {
                    if ( jqXHR.responseJSON.deleted )
                    {
                        $('#'+event_hash).addClass('alert-danger');
                        tr.find('input[name=kassirclub_sector]').val('');
                    }
                    alert_error.html(jqXHR.responseJSON.error);
                }
                else
                {
                    alert_error.html(jqXHR.responseText);
                }
                alert_error.slideDown();
            });
            td.find('button').hide();
        }

        function loadLocationSectorsTable(el, event_hash) {
            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'event_hash' : event_hash,
            };
            $.ajax({
                method : 'POST',
                url : '/sectors/get-location-sectors-table',
                data : data,
                dataType : 'html'
            }).done(function( data ) {
                el.html(data);
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
        $(document).ready(function() {

            $('body').find('.sectors-table').each(function () {
//                console.log($(this).data('load-table'));
                if ( $(this).data('load-table'))
                {
                    loadLocationSectorsTable($(this), $(this).data('event-hash'))
                }
            });

            $("input[name=kassirclub_event]").on('keypress', function (e) {
                if (e.keyCode == 13)
                {
                    associateEvent(this);
                }
            });

            $("input[name=kassirclub_location]").on('keypress', function (e) {
                if (e.keyCode == 13)
                {
                    associateLocation(this);
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

        function getKassirclubSectors (e)
        {
            var td = $(e).parent().parent();
            var sector_select = e;
            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'kassirclub_location_id' : td.find('input[name=kassirclub_location_id]').val(),
            };
            $.ajax({
                method : 'POST',
                url : '/sectors/get-kassirclub-location-sectors',
                data : data,
                dataType : 'json'
            }).done(function( data ) {
//                console.log( data );
                $(sector_select).catcomplete({
                    delay: 0,
                    source: data,
                    minLength: 0
                })
                $(sector_select).trigger(jQuery.Event("keydown"));;
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

        function enterSector(elem, event)
        {
//            console.log(event.keyCode);
            if (event.keyCode == 13)
            {
                associateSector(elem);
            }
        }

        function associateRate(e)
        {
            var td = $(e).parent().parent();
            td.find('button').hide();
            var tr = td.parent();
            var input = $(e);
            var event_hash = tr.find('input[name=event_hash]').val();
            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'event_hash' : event_hash,
                'donor_sector_id' : tr.find('input[name=donor_sector_id]').val(),
                'rate' : $(e).val(),
            };
//                console.log(data);
            $.ajax({
                method : 'POST',
                url : '/sectors/set-rate',
                data : data,
                dataType : 'json'
            }).done(function( data ) {
                console.log( data );
                input.addClass('alert-success');
            }).error(function( jqXHR) {
                if (jqXHR.status == 500) {
                    input.addClass('alert-danger');
                    alert(jqXHR.responseJSON.error);
                }
                else
                {
                    alert(jqXHR.responseText);
                }
            });
            td.find('button').hide();
        }

        function setRate(elem, event)
        {
            if (event.keyCode == 13)
            {
                associateRate(elem);
            }
        }

        function associateMarkup(e)
        {
            var td = $(e).parent().parent();
            td.find('button').hide();
            var tr = td.parent();
            var input = $(e);
            var event_hash = tr.find('input[name=event_hash]').val();
            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'event_hash' : event_hash,
                'donor_sector_id' : tr.find('input[name=donor_sector_id]').val(),
                'markup' : $(e).val(),
            };
//                console.log(data);
            $.ajax({
                method : 'POST',
                url : '/sectors/set-markup',
                data : data,
                dataType : 'json'
            }).done(function( data ) {
                input.removeClass('alert-danger');
                input.addClass('alert-success');
                console.log( data );
            }).error(function( jqXHR) {
                input.removeClass('alert-success');
                input.addClass('alert-danger');
                if (jqXHR.status == 500) {
                    alert(jqXHR.responseJSON.error);
                }
                else
                {
                    alert(jqXHR.responseText);
                }
            });
            td.find('button').hide();
        }

        function setMarkup(elem, event)
        {
            if (event.keyCode == 13)
            {
                associateMarkup(elem);
            }
        }

        function eventParseitChecked(elem)
        {
            var td = $(elem).parent();
            var tr = td.parent();
            var event_hash = tr.find('input[name=event_hash]').val();

            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'event_hash' : event_hash,
                'parseit' : $( elem ).prop( "checked" ),
            };
            console.log();
            $.ajax({
                method : 'POST',
                url : '/events/parseit-off-on',
                data : data,
                dataType : 'json'
            }).done(function( data ) {
                if ( ! data.moderation )
                {
                    $('#'+event_hash).removeClass('alert-danger');
                }
                else
                {
                    $('#'+event_hash).addClass('alert-danger');
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

        $( function() {
            $.widget( "custom.catcomplete", $.ui.autocomplete, {
                _create: function() {
                    this._on(this.element, {
                        focus: function(event) {
                            this.search();
                        }
                    });
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
            var events = [
                    @foreach($kassirclub_events as $kassirclub_event)
                { label: '{{ $kassirclub_event->event }} [{{$kassirclub_event->kassirclub_event_id}}]'},
                @endforeach
            ];

            $( "input[name=kassirclub_event]" ).catcomplete({
                delay: 0,
                source: events,
                minLength: 0
            });

            var locations = [
                    @foreach($kassirclub_locations as $kassirclub_location)
                { label: '{{ $kassirclub_location->location }} [{{$kassirclub_location->kassirclub_location_id}}]'},
                @endforeach
            ];

            $( "input[name=kassirclub_location]" ).catcomplete({
                delay: 0,
                source: locations,
                minLength: 0
            });
        } );
    </script>
@endpush
