<x-app-layout>
    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/bootstrapDatatables.css') }}">
    @stop
    @section('scripts')
        <script src="{{asset('js/jQueryDatatables.js')}}"></script>
        <script src="{{asset('js/bootstrapDatatables.js')}}"></script>
        <script>
            $(document).ready(function () {
                $('#letter-info').DataTable({
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 'no-sort'
                    },
                        {
                            "searchable": false,
                            "targets": 'no-search'
                        }],
                    "order": [[6, 'desc']],
                });
            });
        </script>
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Letters <a class="btn-sm btn-outline-primary" href="{{route('letter.create')}}">Add</a>
        </h2>
    </x-slot>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">

            <table id="letter-info" class="compact table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>Ref no.</th>
                    <th>To</th>
                    <th>Address</th>
                    <th>Subject</th>
                    <th>Body</th>
                    <th class="no-sort">Signed By</th>
                    <th>Designation</th>
                    <th class="no-sort">Created By</th>
                    @if(auth()->user()->role == 'admin')
                        <th class="no-sort">Action</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @forelse($letters as $letter)
                    <tr>
                        <td>
                            <a href="{{route('letter.show', [$letter->id])}}"> {{$letter->ref_no}}</a>
                        </td>
                        <td><a href="{{route('letter.show', [$letter->id])}}">{{$letter->to}}</a></td>
                        <td><a href="{{route('letter.show', [$letter->id])}}">{{$letter->address}}</a></td>
                        <td><a href="{{route('letter.show', [$letter->id])}}">{{$letter->subject}}</a></td>
                        <td>
                            <a href="{{route('letter.show', [$letter->id])}}">{{str_limit(strip_tags($letter->body), 20)}}</a>
                        </td>
                        <td><a href="{{route('letter.show', [$letter->id])}}">{{$letter->signed_by}}</a></td>
                        <td><a href="{{route('letter.show', [$letter->id])}}">{{$letter->designation}}</a></td>
                        <td><a href="{{route('employee.show', [$letter->user_id])}}">{{$letter->user->name}}</a></td>

                        @if(auth()->user()->role == 'admin')
                            <td>
                                {{ Form::open(['url' => route('letter.destroy', $letter), 'method' => 'delete']) }}
                                <a class="btn btn-link btn-sm" href="{{route('letter.edit',  $letter)}}">Edit </a>
                                <button class="btn-sm btn-outline-danger" onclick="confirm('Are you sure?')">Delete
                                </button>
                                {{ Form::close() }}

                            </td>

                        @endif
                    </tr>
                @empty
                    <tr>No Letters Found</tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
