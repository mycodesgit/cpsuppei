@extends('layouts.master')

@section('body')

@php $cr = request()->route()->getName(); @endphp

<div class="container-fluid">
    <div class="row" style="padding-top: 100px;">
        <div class="col-lg-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 17pt"></h5>
                    @include('partials.control_reportsSidebar')
                </div>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('parOptionReportGen') }}" class="form-horizontal add-form" id="parReport" method="POST" target="_blank">
                        @csrf
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label>Campus or Office:</label>
                                    <select class="form-control select2bs4" id="campus_id" name="campus_id" style="width: 100%;">
                                        <option disabled selected value=""> --- Select Campus or Office Type --- </option>
                                        @foreach ($office as $data)
                                            <option value="{{ $data->id }}">{{ $data->office_abbr }} - {{ $data->office_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label>End User:</label>
                                    <select onchange="selItem(this.value)" class="form-control select2bs4" id="person_accnt" name="person_accnt" style="width: 100%;">
                                        <option disabled selected value=""> --- Select End User --- </option>
                                        @foreach ($accnt as $data)
                                            <option value="{{ $data->id }}">{{ $data->person_accnt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label>Item:</label>
                                    <select class="form-control select2bs4" multiple="multiple" id="item_id" name="item_id[]" style="width: 100%;" required>
                                       
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <button type="reset" class="btn btn-danger" data-dismiss="modal">
                                        Reset
                                    </button>
                                    <button type="submit" name="btn-submit" class="btn btn-primary">
                                        <i class="fas fa-file-pdf"></i> Generate
                                    </button>
                                </div>
                            </div>
                        </div>   
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

function selItem(val) {
    var urlTemplate = "{{ route('itemList', [':id']) }}";
    var url = urlTemplate.replace(':id', val);

    $.ajax({
        url: url,
        type: "GET",
        success: function(response) {
            //console.log(response);
            $('#item_list').empty();
            $('#item_list').append(response.options);
        }
    });
}
</script>

@endsection