<table class="table table-striped">
    <tr>
        <th></th>
        <th>Сектор Кассирклаба</th>
        <th>Коэффициент и наценка</th>
    </tr>
    @foreach($sectors as $donor_sector)
        <tr>
            <td>{{$donor_sector->sector}}</td>
            <td align="center">
                <input type="hidden" readonly class="form-control " name="sector" value="{{$donor_sector->sector}}" />
                <input type="hidden" readonly class="form-control " name="event_hash" value="{{$event_hash}}" />
                {{--<input type="hidden" readonly class="form-control " name="kassirclub_sector_id" value="{{ $donor_sector->kassirclubSector()->kassirclub_sector_id }}" />--}}
                <input type="hidden" readonly class="form-control " name="kassirclub_location_id" value="{{ $kassirclub_location_id }}" />
                <div class="input-group">
                    {{--<input type="text" class="form-control " onfocus="getKassirclubSectors(this)" onkeypress="enterSector(this,event)" name="kassirclub_sector" value="{{$donor_sector->kassirclubSector()->sector}}" />--}}
                    <input oninput="addButton(this)" type="text" class="form-control " onfocus="getKassirclubSectors(this);inputFocus(this);" onkeypress="enterSector(this,event)" name="kassirclub_sector" value="{{$donor_sector->associateSector($kassirclub_location_id)->sector}}" />
                    <span class="input-group-btn">
                        <button onclick="associateSector($(this).parent().parent().find('input[name=kassirclub_sector]'))" style="display: none" type="button" class="btn btn-success">ok</button>
                    </span>
                </div>
                <a target="_blank" href="/kassir-club-api/get-events-info">upd</a>
                <input readonly type="text" class="form-control alert-success" value="" style="display: none" />
                <label class="alert-danger" style="display: none"></label>
            </td>
            <td>
                <input type="hidden" readonly class="form-control " name="donor_sector_id" value="{{$donor_sector->id}}" />
                <input type="hidden" readonly class="form-control " name="event_hash" value="{{$event_hash}}" />
                <div class="input-group">
                    <input oninput="addButton(this)" onfocus="inputFocus(this);" type="number" min="0" step="0.1" class="form-control " onkeypress="setRate(this,event)" name="rate" value="{{$donor_sector->rateAndMarkupEventSector($event_hash)->rate}}" />
                    <span class="input-group-btn">
                        <button onclick="associateRate($(this).parent().parent().find('input[name=rate]'))" style="display: none" type="button" class="btn btn-success">ok</button>
                    </span>
                </div>
                <div class="input-group">
                    <input oninput="addButton(this)" onfocus="inputFocus(this);" type="number" min="0" class="form-control " onkeypress="setMarkup(this,event)" name="markup" value="{{$donor_sector->rateAndMarkupEventSector($event_hash)->markup}}" />
                    <span class="input-group-btn">
                        <button onclick="associateRate($(this).parent().parent().find('input[name=markup]'))" style="display: none" type="button" class="btn btn-success">ok</button>
                    </span>
                </div>
            </td>
        </tr>
    @endforeach
</table>