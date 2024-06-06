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
                        <i class="fas fa-file-pdf"></i> ICS Reports
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('icsOptionReportGen') }}" class="form-horizontal add-form" id="icsReport" method="POST" target="_blank">
                        @csrf
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label>Campus or Office:</label>
                                    <select class="form-control select2bs4" id="office_id" name="office_id" style="width: 100%;" onchange="icsgenOption(this.value, 'campus', this.options[this.selectedIndex].getAttribute('data-person-cat'))">
                                        <option disabled selected value=""> --- Select Campus or Office Type --- </option>
                                        @foreach ($office as $data)
                                            @if($data->id != 1)
                                                <option value="{{ $data->id }}"  data-person-cat='none'>{{ $data->office_abbr }} - {{ $data->office_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Category:</label>
                                    <select id="category_id" name="categories_id" onchange="categor(this.value)" data-placeholder="---Select Category---" class="form-control select2bs4" style="width: 100%;">
                                        <option></option>
                                        
                                        <option value="All">All</option>
                                        @foreach ($category as $data)
                                            <option value="{{ $data->cat_code }}">
                                                {{ $data->cat_code }} - {{ $data->cat_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6" id="account-div">
                                    <label>Account Title:</label>
                                    <select id="account_title" name="property_id" data-placeholder="---Select Account Title---" class="form-control select2bs4" style="width: 100%;">
                                    </select>
                                </div>
                                <input type="hidden" id="selected_account_id" name="selected_account_id">
                                <div class="col-md-6">
                                    <label>Date Range:</label>
                                    <div class="input-group">
                                        <div class="sdate col-md-6">
                                            <input type="date" name="start_date_acquired" class="form-control" placeholder="Start Date">
                                        </div>
                                        <div class="edate col-md-6">
                                            <input type="date" name="end_date_acquired" class="form-control" placeholder="End Date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label>End User:</label>
                                    <input type="hidden" id="accountType" name="pAccountable">
                                    <select class="form-control select2bs4" id="person_accnt" data-placeholder="Select Accountable" onchange="icsgenOption(this.value, 'user', this.options[this.selectedIndex].getAttribute('data-person-cat'))" name="person_accnt" style="width: 100%;">
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
function categor(val) {
    var categoryId = val;
    var propertyId = $("#property_id").val();
    $("#selected_account_id").val('All');
    var modeval;
    if (propertyId === '2') {
        modeval = 2;
    } else if (propertyId === '3') {
        modeval = 3;
    } else if (propertyId === '1') {
        modeval = 1;
    } else {
        modeval = 3; 
    }

    var urlTemplate = "{{ route('inventoryCat', [':id', ':mode']) }}";
    var url = urlTemplate.replace(':id', categoryId).replace(':mode', modeval);
    
    if (categoryId) {
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                console.log(response);
                $('#account_title').empty();
                $('#account_title').append("<option value=''></option>");
                $('#account_title').append(response.options);
            }
        });
        $("#account_title").on("change", function() {
            var selectedOption = $(this).find(':selected');
            var selectedAccountId = selectedOption.val();
            var selectedAccountCode = selectedOption.data('account-id');
            $("#selected_account_id").val(selectedAccountCode);
        });
    }
}
</script>
<script>
function icsgenOption(val, type, pAccountable) {
    var endUserID = val;

    var urlTemplate = "{{ route('icsgenOption') }}";
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
                    $('#person_accnt').append("<option value=''></option>");
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