@if(in_array($cur_viewSidebar_route, ['itemRead', 'itemEdit']))

<script>
    $(document).on('click', '.item-delete', function(e){
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
                    url: "{{ route('itemDelete', ":id") }}".replace(':id', id),
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

@endif