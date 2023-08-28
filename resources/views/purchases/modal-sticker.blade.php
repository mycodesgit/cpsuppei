<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>

	<style>
		#sticker {
		  	font-family: Bookman Old Style, Georgia, serif;
		  	border-collapse: collapse;
		  	width: 100%;
		  	margin-top: 20px;
		  	color: #000;
		}

		#sticker td {
			border: 1px solid #000;
		  	padding: 4px;
		} 
		#sticker th {
		  	border: 1px solid #000;
		  	padding: 4px;
		}

		#sticker tfoot {
		  	border: 2px solid #000;
		  	padding: 4px;
		}

		#sticker th {
		  	padding-top: 1px;
		  	padding-bottom: 1px;
		  	font-size: 10pt;
		}
		.sticker-text-label {
			text-align: center;
		}
		.sticker-label {
			text-align: left;
		}
		.logo-sticker {
            width: 40px;
            float: center;
        }
        .colortableyellow {
        	background-color: yellow;
        }
        .colortablegreen {
        	background-color: #008000;
        }
        .dataText {
        	font-style: italic;
        	font-size: 9pt;
        	text-decoration: underline;
        }
	</style>
</head>
<body>
	<div class="colortable" style="background-color: {{ $propertiesId == 3 ? 'yellow' : 'green' }}">
		<table id="sticker">
			<thead>
				<tr>
					<th rowspan="1" class="sticker-text-label">
						@if($setting->photo_filename)
		                    <img src="{{ asset('uploads/'. $setting->photo_filename) }}" class="logo-sticker brand-image img-circle elevation-3" alt="Uploaded Photo">
		                @else
		                    <img class="logo-sticker img-circle" src="{{ asset('template/img/default-logo.png') }}" alt="User profile picture">
		                @endif
					</th>
					<th colspan="3" class="sticker-text-label" style="font-size: 11pt">Central Philippines State University</th>
				</tr>
				<tr>
					<th rowspan="8" class="sticker-text-label"><canvas id="qrcode" class="elevation-3"></canvas></th>
					<th><b class="sticker-label">Property No.:</b> <span class="dataText">{{ $purchase->property_no_generated  }}</span class=""></th>
				</tr>
				<tr>
					<th>Classification: <span class="dataText">{{ $purchase->account_title_abbr  }}</span></th>
				</tr>
				<tr>
					<th>Item/Model/Brand: <span class="dataText">{{ $purchase->item_model  }}</span></th>
				</tr>
				<tr>
					<th>Serial No.: <span class="dataText">{{ $purchase->serial_number  }}</span></th>
				</tr>
				<tr>
					<th>Acquisition Cost: <span class="dataText">{{ $purchase->item_cost  }}</span></th>
				</tr>
				<tr>
					<th>Acquisition Date: <span class="dataText">{{ $purchase->date_acquired  }}</span></th>
				</tr>
				<tr>
					<th>Person Accountable: <span class="dataText">{{ $purchase->office_officer  }}</span></th>
				</tr>
				<tr>
					<th>Validation Sign: <span class="dataText"></span></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="5" class="sticker-text-label">*Removing or tampering of this sticker is punishable by Law*</td>
				</tr>
			</tbody>
		</table>
	</div>

<script>
    var inputText= "{{ $purchase->property_no_generated  }}";
    var qr = new QRious({
        element: document.getElementById("qrcode"),
        value: inputText,
        size: 90,
    });
</script>


<script>
    $('#downloadStickerButton').click(function() {
        downloadSticker();
    });

    function downloadSticker() {
        const contentToCapture = document.getElementById('sticker');
        contentToCapture.style.backgroundColor = "{{ $propertiesId == 3 ? 'yellow' : 'green' }}";
        html2canvas(contentToCapture).then(function(canvas) {
            const imageData = canvas.toDataURL('image/png');
            contentToCapture.style.backgroundColor = '';
            const downloadLink = document.createElement('a');
            downloadLink.href = imageData;
            downloadLink.download = 'sticker.png';
            triggerDownload(downloadLink);
        });
    }

    function triggerDownload(link) {
        const event = new MouseEvent('click', {
            view: window,
            bubbles: true,
            cancelable: true
        });
        link.dispatchEvent(event);
    }
</script>

</body>
</html>