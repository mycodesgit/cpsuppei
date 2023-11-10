<div class="printarea">
    <header class="bdHead">
        <div class="row">
            <div class="logo-left" style="padding-left: 20px">
                <img src="{{ asset('/template/img/CPSU_L.png') }}" alt="Left Logo" >
            </div>

            <div class="logo-center col-md-12">
                <p class="text1">Republic of the Philippines</p>
                <p class="text2">CENTRAL PHILIPPINES STATE UNIVERSITY</p>
                <p class="text3">Kabankalan City, Negros Occidental 6111</p>
            </div>
        </div>
    </header>
    <table id="tableprnt" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="col-md-4 bdleft">Property No.</th> <th class="col-md-6 bdright">{{ $purchase->property_no_generated  }}</th>
            </tr>
            <tr>
                <th class="bdleft">Description</th> <th class="bdright">{{ $purchase->item_descrip  }}</th>
            </tr>
            <tr>
                <th class="bdleft">Serial No.</th> <th class="bdright">{{ $purchase->serial_number  }}</th>
            </tr>
            <tr>
                <th class="bdleft">Unit Price</th> <th class="bdright">{{ $purchase->item_cost  }}</th>
            </tr>
            <tr>
                <th class="bdleft">Date Acquired</th> <th class="bdright">{{ $purchase->date_acquired  }}</th>
            </tr>
            <tr>
                <th class="bdleft">Department/Office</th> <th class="bdright">{{ $purchase->office_name  }}</th>
            </tr>
            <tr>
                <th class="bdleft">Person Accountable</th> <th class="bdright">{{ $purchase->person_accnt  }}</th>
            </tr>
        </thead>
    </table>
    <footer>
        <div>
            <div class="qrcode-container">
                <canvas id="qrcode"></canvas>
            </div>
            <div class="prnt-incharge">{{auth()->user()->fname}} {{auth()->user()->lname}}</div>
            <div class="prnt-role">Printed by</div>
            <div class="officer">{{ $user->fname  }} {{ $user->lname  }}</div>
            <div class="officerRole">{{ $user->role  }}</div>
            <div class="stickerlabel">--- Do not remove ---</div>
        </div>
    </footer>
</div>

<script>
    var inputText= "{{ $purchase->property_no_generated  }}";
    var qr = new QRious({
        element: document.getElementById("qrcode"),
        value: inputText,
        size: 90,
    });
</script>