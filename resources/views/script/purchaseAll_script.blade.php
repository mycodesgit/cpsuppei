<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "ajax": "{{ route('getPurchase') }}",
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            "columns": [
                {data: 'id', name: 'id', orderable: false, searchable: false},
                {data: 'abbreviation'},
                {data: 'property_no_generated'},
                {data: 'office_abbr'},
                // {data: 'item_number'},
                {data: 'item_name'},
                {data: 'item_descrip',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return '<div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 8ch;">' + data + '</div>';
                        } else {
                            return data;
                        }
                    }
                },
                {data: 'serial_number',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return '<div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 8ch;">' + data + '</div>';
                        } else {
                            return data;
                        }
                    }
                },
                {data: 'item_cost',
                    render: function(data, type, row) {
                        if (row.price_stat === 'Uncertain') {
                            return '<span style="color: red;">' + data + '</span>';
                        } else {
                            return '<span>' + data + '</span>';
                        }
                    }
                },
                {data: 'qty'},
                {data: 'total_cost'},
                {data: 'date_acquired'},
                {data: 'remarks'},
                {data: 'id',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var editUrl = "{{ route('purchaseEdit', ['id' => ':id']) }}".replace(':id', data);
                            return `
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle dropdown-icon" data-toggle="dropdown"></button>
                                    <div class="dropdown-menu">
                                        <a href="${editUrl}" class="dropdown-item btn-edit" href="#"><i class="fas fa-exclamation-circle"></i> Edit</a>
                                        <button id="${data}" onclick="printSticker(${data})" class="dropdown-item btn-print" href="#"><i class="fas fa-print"></i> Sticker</button>
                                        <button value="${data}" class="dropdown-item purchase-delete" href="#"><i class="fas fa-trash"></i> Delete</button>
                                    </div>
                                </div>
                            `;
                        } else {
                            return data;
                        }
                    }
                }

            ],
            initComplete: function(settings, json) {
                var api = this.api();
                api.column(0, {search: 'applied', order: 'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            },
            "createdRow": function (row, data, dataIndex) {
                $(row).attr('id', 'tr-' + data.id);
            }
        });
    });
</script>

<script>
    $(document).on('click', '.purchase-delete', function(e){
        var id = $(this).val();
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('purchaseDelete', ":id") }}".replace(':id', id),
                        success: function (response) {  
                        $("#tr-"+id).delay(1000).fadeOut();
                        Swal.fire({
                            title:'Deleted!',
                            text:'Successfully Deleted!',
                            type:'success',
                            icon: 'warning',
                            showConfirmButton: false,
                            timer: 1000
                        })
                    }
                });
            }
        })
    });
</script>