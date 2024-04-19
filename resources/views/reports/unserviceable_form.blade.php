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
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-pdf"></i> Unserviceable Reports
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('unserviceReport') }}" class="form-horizontal add-form" id="parReport" method="POST" target="_blank">
                        @csrf
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label>Campus or Office:</label>
                                    <select class="form-control select2bs4" id="campus_id" name="campus_id" style="width: 100%;" onchange="genUnserviceable(this.value, 'campus', this.options[this.selectedIndex].getAttribute('data-person-cat'))">
                                        <option disabled selected value=""> --- Select Campus or Office Type --- </option>
                                        @foreach ($office as $data)
                                            <option value="{{ $data->id }}"  data-person-cat='none'>{{ $data->office_abbr }} - {{ $data->office_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label>End User:</label>
                                    <input type="text" id="accountType" name="pAccountable" >
                                    <select class="form-control select2bs4" id="person_accnt" data-placeholder="Select Accountable" onchange="genUnserviceable(this.value, 'user', this.options[this.selectedIndex].getAttribute('data-person-cat'))" name="person_accnt" style="width: 100%;">
                                        <option></option>
                                     
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label>Item:</label>
                                    <select class="select2bs4" multiple="multiple" data-placeholder="Select Items" id="item_id" name="item_id[]" style="width: 100%;" required>
                                        <option value='All'>All</option>
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
function genUnserviceable(val, type, pAccountable) {
    var endUserID = val;

    var urlTemplate = "{{ route('genOption') }}";
    var csrfToken = '{{ csrf_token() }}'; 
    if(pAccountable != "none"){
        $('#accountType').val(pAccountable);
    }
    //alert(pAccountable);
    if (endUserID) {
        $.ajax({
            url: urlTemplate,
            type: "POST",
            data: {
                'id': endUserID,
                'type': type,
                'pAccountable': pAccountable,
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken 
            },
            success: function(response) {
                console.log(response);
                if(type == 'campus'){
                    $('#person_accnt').empty();
                    $('#person_accnt').append("<option value='All'>All</option>");
                    $('#person_accnt').append(response.options);
                }else{
                     $('#item_id').empty();
                     $('#item_id').append("<option value=''></option>");
                     $('#item_id').append(response.options);
                }
            }
        });
        
    }
};
</script>


@endsection