<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Employees <a class="btn-sm btn-outline-primary" href="{{route('employee.create')}}">Add</a>
        </h2>
    </x-slot>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <table id="employee-info" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Monthly Salary</th>
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
                        <td><a href="{{route('employee.show', $employee->id)}}"> {{$employee->name}}</a></td>
                        <td>{{$employee->username}}</td>
                        <td>{{$employee->monthly_salary}}</td>
                        <td><a class="btn-sm btn-outline-dark" href="{{route('employee.edit',  $employee)}}">Edit </a>
                            {{ Form::open(['url' => route('employee.destroy', $employee), 'method' => 'delete']) }}
                            <button class="btn-sm btn-outline-danger d-none" onclick="alert('Are you sure?')">Delete
                            </button>
                            {{ Form::close() }}
                        </td>

                    <?php $i++;?>
                @empty
                    <tr>No Employees Found</tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
