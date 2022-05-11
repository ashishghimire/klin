<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Services <a class="btn-sm btn-outline-primary" href="{{route('service.create')}}">Add</a>
        </h2>
    </x-slot>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif


    <table id="service" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>S.N.</th>
            <th>Service</th>
            <th>Rate</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1;?>
        @forelse($services as $service)
            <tr>
                <td>
                    {{$i}}
                </td>
                <td>{{$service->name}}</td>
                <td>{{$service->rate}} per {{$service->unit}}</td>
                <td><a class="btn-sm btn-outline-dark" href="{{route('service.edit',  $service)}}">Edit </a>
                    {{ Form::open(['url' => route('service.destroy', $service), 'method' => 'delete']) }}
                    <button class="btn-sm btn-outline-danger" onclick="confirm('Are you sure?')">Delete</button>
                    {{ Form::close() }}
                </td>

            <?php $i++;?>
        @empty
            <tr>No Services Found</tr>
        @endforelse
        </tbody>
    </table>

</x-app-layout>
