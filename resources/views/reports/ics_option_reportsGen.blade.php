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
		  	font-family: Arial;
		  	border-collapse: collapse;
		  	width: 100%;
		  	font-size: 11pt;
		}

		#rpcppe td {
			border: 1px solid #000;
		  	padding: 3px;
		} 
		#rpcppe th {
		  	border: 1px solid #000;
		  	/*padding: 8px;*/
		}
		.icsno{
			text-align: right !important;
			font-size: 8pt;
		}
		.text-total {
			font-size: 12pt !important;

		}
		#rpcppe tfoot {
		  	border: 1px solid #000;
		  	padding: 8px;
		}

		#rpcppe tr:nth-child(even){background-color: #f2f2f2;}

		#rpcppe tr:hover {background-color: #ddd;}

		#rpcppe th {
		  	padding-top: 12px;
		  	padding-bottom: 12px;
		  	text-align: center;
		  	background-color: #fff;
		  	font-size: 10pt;
		}
		.footer-cell {
			width: 32%;
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
		}
		.sign {
			height: 80px;
		}
		.text-receivedby {
			font-size: 8pt;
		}
	</style>
</head>
<body>
	<header style="margin-top: -40px; margin-left: ;">
		<img src="{{ asset('template/img/ics.png') }}">
	</header>

	<div class="table-responsive">
		<table id="rpcppe" class="table table-bordered">
			<thead>
				<tr>
					<th colspan="8"><h3>INVENTORY CUSTODIAN SLIP</h3><p class="icsno" style="margin-bottom: -6px;">ICS No. _________________</p></th>
				</tr>
				<tr>
					<th>NO</th>
					<th>Qty</th>
					<th>Unit</th>
					<th>Description</th>
					<th>Unit Cost</th>
					<th>Date Acquired</th>
					<th>Inventory Item No.</th>
					<th width="30">Estimated Useful Life</th>
				</tr>
			</thead>
			<tbody>
				@php
					$maxRows = 30;
					$rowCount = 0;
					$overallTotal = 0;
				@endphp

				@if (!$purchase->isEmpty())
					@php $no = 1; $rowCount = 0;  @endphp
					@foreach ($purchase as $purchaseData)
						<tr>
							<td>{{ $no++ }}</td>
						    <td>{{ $purchaseData->qty }}</td>
						    <td>{{ $purchaseData->unit_name }}</td>
						    <td>{{ $purchaseData->item_descrip }}</td>
						    <td>{{ $purchaseData->item_cost }}</td>
						    <td>{{ $purchaseData->date_acquired }}</td>
						    <td>{{ $purchaseData->property_no_generated }}</td>
						    <td></td>
						    @if (is_numeric(str_replace(',', '', $purchaseData->item_cost)))
						        @php $overallTotal += str_replace(',', '', $purchaseData->item_cost); @endphp
						    @endif
						</tr>
					@if (is_numeric(str_replace(',', '', $purchaseData->item_cost)))
					    @php $rowCount++; @endphp
					@endif
					@endforeach
				@endif

				@php
					$emptyRows = $maxRows - $rowCount;
				@endphp

				@for ($i = 0; $i < $emptyRows; $i++)
					<tr>
					    <td height="13"></td>
					    <td></td>
					    <td></td>
					    <td></td>
					    <td></td>
					    <td></td>
					    <td></td>
					    <td></td>
					</tr>
				@endfor
				<tr>
			    	<th colspan="4" style="text-align: right"><b class="text-total">Total:</b></th>
			    	<th colspan="4" style="text-align: left"><b class="text-total">{{ number_format($overallTotal) }}</b></th>
			    </tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" class="sign" style="text-align: center;">
						<span class="text-receivedby" style="float: left">Received by:</span><br>
						 <span class="footer-cell">
							<span class="footer-cell-sign">____________________</span><br>
							<span class="footer-cell-text">Signature Over Printed Name</span><br><br>

							<span class="footer-cell-sign">____________________</span><br>
							<span class="footer-cell-text">Positon / Office</span><br><br>

							<span class="footer-cell-sign">____________________</span><br>
							<span class="footer-cell-text">Date</span>
						</span>
					</td>
					<td colspan="4" class="sign" style="text-align: center;">
						<span class="text-receivedby" style="float: left">Received from:</span><br>
						 <span class="footer-cell">
							<span class="footer-cell-sign"><u>MA. SOCORRO T. LLAMAS</u></span><br>
							<span class="footer-cell-text">Signature Over Printed Name</span><br><br>

							<span class="footer-cell-sign"><u>SUPPLY OFFICER / Supply Office</u></span><br>
							<span class="footer-cell-text">Positon / Office</span><br><br>

							<span class="footer-cell-sign"><u>{{ \Carbon\Carbon::now()->format('M. j, Y') }}</u></span><br>
							<span class="footer-cell-text">Date</span>
						</span>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</body>
</html>