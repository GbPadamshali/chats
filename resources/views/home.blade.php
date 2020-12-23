@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- {{ __('You are logged in!') }} --}}
                    <table class="table" id="users_table">
                        <thead class="thead-dark">
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <td>1</td>
                                <td>Test User</td>
                                <td>test@example.com</td>
                                <td>123456</td>
                                <td>Chat | Mark as all Read</td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <script type="text/javascript">
    $(document).ready(function (){
        $('#users_table').DataTable();
    });
</script> --}}

<script>
    $(document).ready(function () {
        $.noConflict();
        //datatable
        userTable = $('#users_table').DataTable({
            "sDom": '<"float-left"f>tirtp',
            "bLengthChange":false,
            "bInfo": false,
            "processing": true,
            "aaSorting": [],
            "bSortable": true,
            "oLanguage": {
                "sSearch": "",
                "sSearchPlaceholder": "Search....",
                // "sProcessing": "<img src='../assets/global/img/loading-spinner-default.gif'>",
                "sEmptyTable": 'No matching records found'
            },
            "serverSide": true,
            'ajax': {
                url: "{{ route('chat.users') }}",
                type: "GET",
                "data": function (d) {
                },
                complete: function (date) {
               },
                error: function () { //error handling
                }
            },
            "columns": [
                {data:'name', name:'name'},
                {data:'email', name:'email'},
                {data:'mobile', name:'mobile'},
                {data:'action', name:'action', orderable: false, searchable: false},
            ],
        });
        $(".dataTables_processing").css('margin-top', "0");
    })
</script>
@endsection
