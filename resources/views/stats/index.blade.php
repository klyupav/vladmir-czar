@extends('layouts.app')

@section('title')/Статистика@endsection

@section('content')
    <div class="container">
        @if (session('alert-success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('alert-success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <h1>Статистика</h1>
            </div>
        </div>
        <div class="row">
            <table class="table">
                <tr>
                    <th>Донор</th>
                    <th>Мероприятий</th>
                    <th>Модератором сопоставлено</th>
                    <th>Кол-во дат</th>
                    <th>Билетов</th>
                </tr>
                <?php
                $sum_countSourcesByDonor = 0;
                $sum_countSourcesParsingByDonor = 0;
                $sum_countSourcesDateByDonor = 0;
                $sum_countTicketsByDonor = 0;
                ?>
                @foreach($donors as $donor)
                    <?php
                    $countSourcesByDonor = $donor->countSourcesByDonor();
                    $sum_countSourcesByDonor += $countSourcesByDonor;
                    $countSourcesParsingByDonor = $donor->countSourcesParsingByDonor();
                    $sum_countSourcesParsingByDonor += $countSourcesParsingByDonor;
                    $countSourcesDateByDonor = $donor->countSourcesDateByDonor();
                    $sum_countSourcesDateByDonor += $countSourcesDateByDonor;
                    $countTicketsByDonor = $donor->countTicketsByDonor();
                    $sum_countTicketsByDonor += $countTicketsByDonor;
                    ?>
                    <tr>
                        <td>{{ $donor->donor_class_name }}</td>
                        <td>{{ $countSourcesByDonor }}</td>
                        <td>{{ $countSourcesParsingByDonor }}</td>
                        <td>{{ $countSourcesDateByDonor }}</td>
                        <td>{{ $countTicketsByDonor }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th>Итого:</th>
                    <th>{{ $sum_countSourcesByDonor }}</th>
                    <th>{{ $sum_countSourcesParsingByDonor }}</th>
                    <th>{{ $sum_countSourcesDateByDonor }}</th>
                    <th>{{ $sum_countTicketsByDonor }}</th>
                </tr>
            </table>
            {{--<p class="text-right"> {{$sources->lastItem()}} из {{$sources->total()}}</p>--}}
            {{--{{$sources->appends(request()->input())->links()}}--}}
        </div>
    </div>

@endsection

@push('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>

</script>
@endpush
