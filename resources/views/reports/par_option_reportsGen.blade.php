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
			height: 9px;
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
	<p style="margin-top: -30px; margin-left: 640px;">Appendix 71</p>
	<header style="margin-top: -10px; margin-left: 50px;">
		<img src="{{ asset('template/img/par.png') }}">
	</header>
	<p style="font-weight: bolder; font-family: sans-serif; text-align: center;">PROPERTY ACKNOWLEDGEMENT RECEIPT</p>

	<div>
		<p style="font-weight: bolder; font-family: sans-serif; font-size: 10pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			@if (!$relatedItems->isEmpty())
			    <p style="font-weight: bolder; font-family: sans-serif; font-size: 10pt;">
			        Entity Name: {{ $relatedItems[0]->office_abbr }}
			    </p>
			@else
			    <p>No related items found.</p>
			@endif

		</p>
		<p style="font-weight: bolder; font-family: sans-serif; font-size: 10pt; margin-top: -28px;">Entity Name: ___________________________________________________</p>
		<p style="font-weight: bolder; font-family: sans-serif; font-size: 10pt;">Fund Cluster: __________________________________________________  PAR No.: _______________________</p>
	</div>
	<div class="table-responsive">
		<table id="rpcppe" class="table table-bordered">
			<thead>
				<tr>
					<th width="10%">Quantity</th>
					<th width="8%">Unit</th>
					<th width="30%">Description</th>
					<th>Property Number</th>
					<th width="13%">Date Acquired</th>
					<th width="13%">Amount</th>
				</tr>
			</thead>
			<tbody>
				@php
			        $maxRows = 20;
			        $rowCount = 0;
			        $overallTotal = 0;
			    @endphp

			    @foreach ($relatedItems as $relatedItem)
			        <tr>
			            <td>{{ $relatedItem->qty }}</td>
			            <td>{{ $relatedItem->unit_name }}</td>
			            <td>{{ $relatedItem->item_name }} {{ $relatedItem->item_descrip }} {{ $relatedItem->item_model }} S/N:{{ $relatedItem->serial_number }}</td>
			            <td>{{ $relatedItem->property_no_generated }}</td>
			            <td>{{ $relatedItem->date_acquired }}</td>
			            <td align="right"><b>{{ $relatedItem->item_cost }}</b></td>

			            @if (is_numeric(str_replace(',', '', $relatedItem->item_cost)))
			                @php $overallTotal += str_replace(',', '', $relatedItem->item_cost); @endphp
			            @endif
			        </tr>

			        @if (is_numeric(str_replace(',', '', $relatedItem->item_cost)))
			            @php $rowCount++; @endphp
			        @endif
			    @endforeach

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
			        </tr>
			    @endfor
				<tr>
			    	<td colspan="3" style="text-align: right"><b class="text-total">Supplier:</b></td>
			    	<td colspan="3" style="text-align: left"><b class="text-total"></b></td>
			    </tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" class="sign" style="text-align: center;">
						<span class="text-receivedby" style="float: left">Received by:</span><br>
						 <span class="footer-cell">
							<span class="footer-cell-sign" style="text-decoration: underline;">{{ $relatedItem->person_accnt }}</span><br>
							<span class="footer-cell-text">Signature Over Printed Name</span><br><br>

							<span class="footer-cell-sign">____________________</span><br>
							<span class="footer-cell-text">Positon / Office</span><br><br>

							<span class="footer-cell-sign">____________________</span><br>
							<span class="footer-cell-text">Date</span>
						</span>
					</td>
					<td colspan="3" class="sign" style="text-align: center;">
						<span class="text-receivedby" style="float: left">Issued by:</span><br>
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