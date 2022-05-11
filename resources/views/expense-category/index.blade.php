<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Expense Category <a class="btn-sm btn-outline-primary" href="{{route('expense-category.create')}}">Add</a>
        </h2>
    </x-slot>

    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif


    <table id="expense-category" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>S.N.</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1;?>
        @forelse($categories as $category)
            <tr>
                <td>
                    {{$i}}
                </td>
                <td>{{$category->name}}</td>
                <td><a class="btn-sm btn-outline-dark" href="{{route('expense-category.edit',  $category)}}">Edit </a>
                    {{ Form::open(['url' => route('expense-category.destroy', $category), 'method' => 'delete']) }}
                    <button class="btn-sm btn-outline-danger d-none" onclick="confirm('Are you sure?')">Delete</button>
                    {{ Form::close() }}
                </td>

            <?php $i++;?>
        @empty
            <tr>No Categories Found</tr>
        @endforelse
        </tbody>
    </table>

</x-app-layout>
