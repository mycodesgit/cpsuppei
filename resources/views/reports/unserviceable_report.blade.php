<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<style>
		/*.table-responsive {
		  	overflow-x: auto;
		  	max-width: 100%; 
		}*/
		.text-type {
			text-align: center;
			margin-top: -5px;
		}
		.text1 {
			text-align: center;
			font-size: 12pt;
		}
		.text2 {
			text-align: center;
		}
		.text3 {
			font-size: 12pt;
			margin-top: 10px;
		}
		.text4 {
			font-size: 12pt;
		}

		#unserve {
		  	font-family: Bookman Old Style, Georgia, serif;
		  	border-collapse: collapse;
		  	width: 100%;
		  	margin-top: 20px;
		  	margin-left: -10px;
		}

		#unserve td {
			border: 1px solid #000;
			padding: 2px;
		  	font-size: 8pt;
		} 
		#unserve th {
		  	border: 2px solid #000;
		}

		#unserve tfoot {
		  	border: 2px solid #000;
		  	padding: 8px;
		}



		#unserve tr:hover {background-color: #ddd;}

		#unserve th {
			padding: 2px;
		  	text-align: center;
		  	background-color: #fff;
		  	font-size: 8pt;
		}
		.footer-cell {
			width: 32%;
			float: left;
			text-align: left;
			padding: 5px; 
		}

		.footer-cell-title {
  			font-weight: bold;
		}

		.footer-cell-sign {
  			margin-top: 20px;
		}
		.footer-cell-text {
			font-size: 8pt;
		  	margin-top: 5px;
		  	text-align: left;
		}
		.sign {
			height: 80px;
		}
	</style>
</head>
<body>
	<header style="margin-top: -40px; margin-left: 250px;">
		<img src="{{ asset('template/img/unserviceable-header.png') }}">
	</header>
	<div style="text-align: center; font-size: 12px;" ><b>As of: {{ \Carbon\Carbon::now()->format('F d, Y') }}</b></div>
	<table style="width: 100%; font-size: 12px;">
		<tr>
			<th><b style="float: left; margin-left: -13px;">Entity Name : <span style="display: inline-block; margin-bottom: -3px; width: 260px; text-align:left; border-bottom: 1px solid black;"> CENTRAL PHILIPPINES STATE UNIVERSITY </span></th>
			<th><b style="float: right; margin-right: 10px;">Fund Cluster : <span style="display: inline-block; margin-bottom: -3px; width: 250px; text-align:left; border-bottom: 1px solid black;"> </span></th>
		</tr>
	</table>
	<table style="width: 100%; font-size: 12px; margin-top: 35px;">
		<tr>
			<th style="text-align: center;">
				<span style="display: inline-block; margin-bottom: -3px; width: 160px; "></b>CHIM C. MISAJON</span>
				<br>
				<i>Campus Administrator</i>
			</th>
			<th>
				<span style="display: inline-block; margin-bottom: -3px; width: 160px; "></b>CAMPUS ADMINISTRATOR</span>
				<br>
				<i>(Designation)</i>
			</th>
			<th>
				<span style="display: inline-block; margin-bottom: -3px; width: 160px; "><b>CANDONI CAMPUS</span>
				<br>
				<i>Station</i>
			</th>
		</tr>
	</table>
	<div class="table-responsive">
		<table id="unserve" class="table">
			<thead>
				<tr>
					<th colspan="10">INVENTORY</th>
					<th colspan="8">INSPECTION and DISPOSAL</th>
				</tr>
				<tr style="padding: 2px">
					<th rowspan="2" width="50">Date Acquired</th>
					<th rowspan="2" width="100">Particulars / Article</th>
					<th rowspan="2" >Property No. / Serial Number</th>
					<th rowspan="2" >Qty</th>
					<th rowspan="2" >Unit Cost</th>
					<th rowspan="2" >Total Cost</th>
					<th rowspan="2" >Accumulated Depreciation</th>
					<th rowspan="2"> Accumulated Impairment Losses</th>
					<th rowspan="2"> Carrying Amount</th>
					<th rowspan="2"> Remarks</th>
					<th colspan="5" style="height: 10px !important;"> DISPOSAL</th>
					<th rowspan="2"> Appraised Value</th>
					<th colspan="2"> RECORDS OF SALE</th>
				</tr>
				<tr>
					<th >Sale</th>
					<th >Transfer</th>
					<th >Destruction</th>
					<th >Other (Specisfy)</th>
					<th >Total</th>

					<th >OR No. </th>
					<th >Amount </th>
				</tr>
				<tr>
					@for($i = 1; $i <= 18; $i++)
					<th>{{ $i }}</th>
					@endfor
				</tr>				
			</thead>
			<tbody>
				@foreach ($relatedItems as $relatedItem)
				<tr>
					<td>{{$relatedItem->date_acquired }}</td>
					<td>
						<b>{{ $relatedItem->item_name }}</b>
						<br><i> {{ $relatedItem->item_descrip }}</i><br>
						<b>MODEL:</b>{{ $relatedItem->item_model ? str_replace('Model:', '', $relatedItem->item_model) : '' }}<br>
						<b>SN : </b> {{ $relatedItem->serial_number }}
					</td>
					<td>{{ $relatedItem->property_no_generated }}</td>
					<td>{{ $relatedItem->qty }}</td>
					<td>{{ $relatedItem->item_cost }}</td>
					<td>{{ $relatedItem->item_cost }}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="10" style="border-bottom: none !important;">
						<div><b>I HEREBY request inspection and disposition, pursuant to Section  79 of PD 1445, of the property enumerated above</div><br<br><br>
					</td>
					<td colspan="4" style="border-bottom: none !important;">
						I CERTIFY that I have inspected each and every article enumerated in this report, and that the disposition made thereof was, in my judgment, the best for the public interest.  													
						<br>
					</td>
					<td colspan="4" style="border-bottom: none !important;">
						I CERTIFY that I have witnessed the disposition of the articles enumerated on this report this <u>{{ \Carbon\Carbon::now()->format('jS') }}</u> of <u>{{ \Carbon\Carbon::now()->format('F') }}</u>, <u>{{ \Carbon\Carbon::now()->format('Y') }}</u>.
					</td>
				</tr>

				<tr>
					<td colspan="5" class="text1"  style="border-top: none !important; border-right: none !important;">
						<div style="text-align: left !important;"><b>Requested by:</div><br><br>
						<span style="display: inline-block; margin-bottom: -3px; width: 160px; "><b>CHIM C. MISAJON </span>
						<br>
						<i>Campus Administrator</i>
					</td>
					<td colspan="5" class="text1"  style="border-top: none !important; border-left: none !important;">
						<div style="text-align: left !important;"><b>Approved by:</div><br><br>
						<span style="display: inline-block; margin-bottom: -3px; width: 160px; "><b>ALADINO C. MORACA, Ph. D.</span>
						<br>
						<i>SUC President</i>
					</td>
					<td colspan="4" class="text1"  style="border-top: none !important;"><br><br>
						<span style="display: inline-block; margin-bottom: -3px; width: 160px;"><b>JEREMIAS G. AGUI </span>
						<br>
						<i>Signature over Printed Name of Inspection Officer</i>
					</td>
					<td colspan="4" class="text1"  style="border-top: none !important;"><br><br>
						<span style="display: inline-block; margin-bottom: -3px; width: 160px; "><b>JEREMIAS G. AGUI </span>
						<br>
						<i>Signature over Printed Name of Inspection Officer</i>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</body>
</html>