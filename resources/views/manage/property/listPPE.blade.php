@extends('layouts.master')

@section('body')

@php $cr = request()->route()->getName(); @endphp

<div class="container-fluid">
    <div class="row" style="padding-top: 100px;">
        <div class="col-lg-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 17pt"></h5>
                    @include('partials.control_viewSidebar')
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="10" class="align-center">No.</th>
                                    <th>Category</th>
                                    <th>Account Title</th>
                                    <th>Account Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $no = 1; @endphp
                            @foreach ($properties as $pro)
                            <tr id="tr-{{ $pro->id  }}" class="{{ $cr === 'ppeEdit' ? $pro->id == $ppeProperties->id ? 'bg-selectEdit' : '' : ''}}">
                                <td>{{ $no++ }}</td>
                                <td>{{ $pro->cat_name }}</td>
                                <td>{{ $pro->account_title }}</td>
                                <td>{{ $pro->account_number }}</td>
                                <td>
                                    <a href="{{ route('ppeEdit', $pro->id) }}" class="btn btn-info btn-xs"><i class="fas fa-info-circle"></i></a>
                                    <button value="{{ $pro->id }}" class="btn btn-danger btn-xs property-delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-plus"></i>{{ $cr == 'ppeEdit' ? 'Edit' : 'Add'}}
                    </h5>
                </div>
                <div class="card-body">
                    <form id="propertyForm" action="{{ $cr == 'ppeEdit' ? route('ppeUpdate') : route('ppeCreate') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Categories </label>
                                    @if ($cr == 'ppeEdit')
                                        <input type="hidden" name="id" value="{{ $ppeProperties->id }}">
                                    @endif
                                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                                    <select id="category" name="category_id" class="form-control select2bs4" data-placeholder="Select Category" style="width: 100%;">
                                        <option value="">-- Select --</option>
                                        @foreach ($categories as $cat)
                                        <option value="{{ $cat->cat_code }}" @if($cr === 'ppeEdit' && $cat->cat_code === $ppeProperties->category_id) selected @endif>
                                            {{ $cat->cat_code }} {{ $cat->cat_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>  

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Account Number : <span class="text-muted">0-00-00-000</span></label>
                                    <input type="text" id="account_number" name="account_number" pattern="[0-9]-[0-9][0-9]-[0-9][0-9]-[0-9][0-9][0-9]" @if($cr == 'ppeEdit') value="{{ $ppeProperties->account_number }}" @endif value="{{ $property->default_code }}-{{ $property->property_code }}-" class="form-control" style="text-transform: capitalize;" placeholder="Enter Category Name" onkeyup="extractLastThree()" oninput="updateInputValue(this, '{{ $property->default_code }}-{{ $property->property_code }}-')">
                                    <input type="hidden" id="code" name="code" value="{{ $cr == 'ppeEdit' ? $ppeProperties->code : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Account title</label>
                                    <input type="text" id="account_title" name="account_title" value="{{ $cr == 'ppeEdit' ? $ppeProperties->account_title : '' }}" class="form-control" style="text-transform: capitalize;" placeholder="Enter Account Title">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="">Account title Abbreviation</label>
                                    <input type="text" id="account_title_abbr" name="account_title_abbr" value="{{ $cr == 'ppeEdit' ? $ppeProperties->account_title_abbr : '' }}" class="form-control" style="text-transform: capitalize;" placeholder="Enter Account Title Abbreviation">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Save
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
    function extractLastThree() {
        var inputString = document.getElementById("account_number").value;
        var lastThreeDigits = inputString.split("-").pop();
        document.getElementById("code").value = lastThreeDigits;
    }
</script>

@endsection