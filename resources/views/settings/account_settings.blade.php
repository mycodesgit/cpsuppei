@extends('layouts.master')

@section('body')
<div class="container-fluid">
    <div class="row" style="padding-top: 100px;">
        <div class="col-lg-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 17pt"></h5>
                    @include('partials.control_settingsSidebar')
                </div>
            </div>
        </div>
        
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" method="post" id="addEmp">

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="exampleInputName">First Name:</label>
                                    <input type="text" name="fname" oninput="this.value = this.value.toUpperCase()" placeholder="Enter First Name" value="{{auth()->user()->fname}}" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label for="exampleInputName">Middle Name:</label>
                                    <input type="text" name="mname" oninput="this.value = this.value.toUpperCase()" placeholder="Enter Middle Name" value="{{auth()->user()->mname}}" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label for="exampleInputName">Last Name:</label>
                                    <input type="text" name="lname" oninput="this.value = this.value.toUpperCase()" placeholder="Enter Last Name" value="{{auth()->user()->lname}}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="exampleInputName">Username:</label>
                                    <input type="text" name="username" placeholder="Enter Username" value="{{auth()->user()->username}}" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label for="exampleInputName">Gender:</label>
                                    <select name="emp_gender" class="form-control">
                                        <option value=""> --- Select --- </option>
                                        <option value="Male" {{auth()->user()->gender == 'Male' ? 'selected="selected"' : '' }}>Male</option>
                                        <option value="Female" {{auth()->user()->gender == 'Female' ? 'selected="selected"' : '' }}>Female</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="exampleInputName">Usertype:</label>
                                    <select name="usertype" class="form-control">
                                        <option value=""> --- Select --- </option>
                                        <option value="Administrator" {{auth()->user()->role == 'Administrator' ? 'selected="selected"' : '' }}>Administrator</option>
                                        <option value="Procurement Officer" {{auth()->user()->role == 'Procurement Officer' ? 'selected="selected"' : '' }}>Procurement Officer</option>
                                        <option value="Staff" {{auth()->user()->role == 'Staff' ? 'selected="selected"' : '' }}>Staff</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <a href="" class="btn btn-success">
                                        <i class="fas fa-save"></i> Update
                                    </a>
                                </div>
                            </div>
                        </div>   
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                        src="{{ asset('template/img/user.png') }}"
                        alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{auth()->user()->fname}}</h3>
                    <a href="#" class="btn btn-default btn-block btn-muted" ><b>{{auth()->user()->role}}</b></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection