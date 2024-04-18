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
		}
		.text2 {
			text-align: center;
		}
		.text3 {
			font-size: 11pt;
			margin-top: 10px;
		}
		.text4 {
			font-size: 11pt;
		}

		#rpcppe {
		  	font-family: Bookman Old Style, Georgia, serif;
		  	border-collapse: collapse;
		  	width: 100%;
		  	margin-top: 20px;
		  	margin-left: -10px;
		}

		#rpcppe td {
			border: 1px solid #000;
			padding: 2px;
		  	font-size: 8pt;
		} 
		#rpcppe th {
		  	border: 2px solid #000;
		}

		#rpcppe tfoot {
		  	border: 2px solid #000;
		  	padding: 8px;
		}

		#rpcppe tr:nth-child(even){background-color: #f2f2f2;}

		#rpcppe tr:hover {background-color: #ddd;}

		#rpcppe th {
		  	padding-top: 12px;
		  	padding-bottom: 12px;
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

	<div class="text-type">


	</div>
	<div class="text1">(Type of Property, Plant and Equipment)</div>
	<div class="text3">Fund Cluster : ________________________________</div>
	<div class="text4">For which <u>ALADINO C. MORACA, Ph.D.</u>,  <u>CPSU, Camingawan, Kabankalan City</u>,  of <u>CENTRAL PHILIPPINES STATE UNIVERSITY</u>,  is accountable, having assumed such accountability on______________.</div>

	<div class="table-responsive">
		<table id="rpcppe" class="table table-bordered">
			<thead>
				<tr style="padding: 2px">
					<th rowspan="2" width="100">Date Acquired</th>
					<th rowspan="2" >Particulars / Article</th>
					<th rowspan="2" >PROPERTY NO.</th>
					<th rowspan="2" >Property No. / Serial Number</th>
					<th rowspan="2" >Qty</th>
					<th rowspan="2" >Unit Cost</th>
					<th rowspan="2" >Total Cost</th>
					<th rowspan="2" >Accumulated Depreciation</th>
					<th colspan="2"> Accumulated Impairment Losses</th>
					<th colspan="2"> Carrying Amount</th>
					<th rowspan="2">Remarks</th>
					<th colspan="2" >Sale</th>
					<th colspan="2" >Transfer</th>
					<th colspan="2" >Destruction</th>
					<th colspan="2" >Other (Specisfy)</th>
					<th colspan="2" >Total</th>
					<th colspan="2" >Appraised </th>
					<th colspan="2" >OR No. </th>
					<th colspan="2" >Amount </th>
				</tr>
				<tr style="padding: 2px">
					<th>Quantity</th>	
					<th>Value</th>
					<th width="100">Whereabout</th>
				</tr>
			</thead>
			<tr>
				<th colspan="6" style="text-align: right">Balance Brought Forwarded - </th>
				<th colspan="6" style="text-align: left"> </th>
			</tr>

			<tfoot>
				<tr>
					<td colspan="12" class="sign">
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