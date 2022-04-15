<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bills
        </h2>
    </x-slot>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif


    <table id="employee-info" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>S.N.</th>
            <th>Name</th>
            <th>Username</th>
            <th class="no-sort">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1;?>
        @forelse($employees as $employee)
            <tr>
                <td>
                    {{$i}}
                </td>
                <td>{{$employee->name}}</td>
                <td>{{$employee->username}}</td>
                <td><a class="btn-sm btn-outline-dark" href="{{route('employee.edit',  $employee)}}">Edit </a>
                    {{ Form::open(['url' => route('employee.destroy', $employee), 'method' => 'delete']) }}
                    <button class="btn-sm btn-outline-danger d-none" onclick="alert('Are you sure?')">Delete</button>
                    {{ Form::close() }}
                </td>

            <?php $i++;?>
        @empty
            <tr>No Employees Found</tr>
        @endforelse
        </tbody>
    </table>

</x-app-layout>
