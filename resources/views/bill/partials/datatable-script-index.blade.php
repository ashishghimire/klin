@if(auth()->user()->role == 'admin')
    <script>
        $(document).ready(function () {

            $('#bill-info').DataTable({
                processing: true,
                serverSide: true,
                "iDisplayLength": 100,
                ajax: {
                    url: "{{route('bill.load-index')}}",
                    data: {
                        laundryStatus: "{{$laundryStatus}}",
                    },
                    complete: function (data) {
                        console.log(data['responseJSON']);
                    },
                },
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                columns: [
                    {data: 'estimate_no', name: 'bills.id'},
                    {data: 'customer_name', name: 'customers.name'},
                    {data: 'phone_no', name: 'customers.phone'},
                    {data: 'services', name: 'bills.service_details', orderable: false, searchable: false},
                    {data: 'amount', name: 'bills.amount'},
                    {data: 'payment_status', name: 'bills.payment_status'},
                    {data: 'payment_mode', name: 'bills.payment_mode'},

                    {data: 'laundry_status', name: 'bills.laundry_status', orderable: false, searchable: false},
                    {data: 'date', name: 'bills.created_at'},
                    {data: 'note', name: 'bills.note'},
                    {data: 'added_by', name: 'users.name'},
                    {data: 'action', orderable: false, searchable: false},
                ],
                columnDefs: [

                    {"width": "10%", "targets": [8, 1]},
                ],
                "order": [[8, 'desc']],
                autoWidth: false
            });
        });

    </script>
@else
    <script>
        $(document).ready(function () {

            $('#bill-info').DataTable({
                processing: true,
                serverSide: true,
                "iDisplayLength": 100,
                ajax: {
                    url: "{{route('bill.load-index')}}",
                    data: {
                        laundryStatus: "{{$laundryStatus}}",
                    }
                },
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                columns: [
                    {data: 'estimate_no', name: 'bills.id'},
                    {data: 'customer_name', name: 'customers.name'},
                    {data: 'phone_no', name: 'customers.phone'},
                    {data: 'services', name: 'bills.service_details', orderable: false, searchable: false},
                    {data: 'amount', name: 'bills.amount'},
                    {data: 'payment_status', name: 'bills.payment_status'},
                    {data: 'payment_mode', name: 'bills.payment_mode'},

                    {data: 'laundry_status', name: 'bills.laundry_status', orderable: false, searchable: false},
                    {data: 'date', name: 'bills.created_at'},
                    {data: 'note', name: 'bills.note'},
                ],
                columnDefs: [

                    {"width": "10%", "targets": [8, 1]},
                ],
                "order": [[8, 'desc']],
                autoWidth: false
            });

        });

    </script>
@endif
