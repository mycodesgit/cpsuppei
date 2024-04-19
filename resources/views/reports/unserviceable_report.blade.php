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
			font-size: 10pt;
		}
		.text2 {
			text-align: center;
		}
		.text3 {
			font-size: 10pt;
			margin-top: 10px;
		}
		.text4 {
			font-size: 10pt;
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

		#unserve tr:nth-child(even){background-color: #f2f2f2;}

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

	<table style="width: 100%; font-size: 14px;">
			<th><b style="float: left; margin-left: -13px;">Entity Name : <span style="display: inline-block; margin-bottom: -3px; width: 250px; text-align:left; border-bottom: 1px solid black;"> </span></th>
			<th><b style="float: right; margin-right: 10px;">Entity Name : <span style="display: inline-block; margin-bottom: -3px; width: 250px; text-align:left; border-bottom: 1px solid black;"> </span></th>
		</tr>
	</table>
	<div class="table-responsive">
		<table id="unserve" class="table">
			<thead>
				<tr>
					<th colspan="11">INVENTORY</th>
					<th colspan="8">INSPECTION and DISPOSAL</th>
				</tr>
				<tr style="padding: 2px">
					<th rowspan="2" width="100">Date Acquired</th>
					<th rowspan="2" >Particulars / Article</th>
					<th rowspan="2" >PROPERTY NO.</th>
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
					@for($i = 0; $i <= 18; $i++)
					<th>{{ $i }}</th>
					@endfor
				</tr>				
			</thead>
			<tfoot>
				<tr>
					<td colspan="19" class="sign">
				        <div class="footer-cell">
							<div class="footer-cell-title">Certified Correct by:</div>
							<div class="footer-cell-sign">MA. SOCORRO T. LLAMAS</div>
							<div class="footer-cell-text">Administrative Officer IV/Supply Officer designate</div>
						</div>

						<div class="footer-cell">
							<div class="footer-cell-title">Approved by:</div>
							<div class="footer-cell-sign">ALADINO C. MORACA, Ph.D.</div>
							<div class="footer-cell-text">SUC President</div>
						</div>

						<div class="footer-cell">
							<div class="footer-cell-title">Verified by</div>
							<div class="footer-cell-sign">JEREMIAS G. AGUI</div>
							<div class="footer-cell-text">State Auditor IV</div>
						</div>
					</td>
					{{-- <td rowspan=""></td> --}}
				</tr>
			</tfoot>
		</table>
	</div>
</body>
</html>