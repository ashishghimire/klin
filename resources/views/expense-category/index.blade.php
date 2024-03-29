<x-app-layout>
    @section('scripts')
        <script>
            function OnClickNextPage(event) {
                var result = confirm("Are you sure ?");
                if (!result) {
                    event.preventDefault(); // prevent event when user cancel
                }
            }
        </script>
    @stop
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

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
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
                        <td>
                            @if(!(strtoupper($category->name) == 'SALARY' || strtoupper($category->name) == 'ALLOWANCE' || strtoupper($category->name) == 'LUNCH' || strtoupper($category->name) == 'CREDITED/ADJUSTED'))
                                <a class="btn-sm btn-outline-dark"
                                   href="{{route('expense-category.edit',  $category)}}">Edit </a>
                                {{ Form::open(['url' => route('expense-category.destroy', $category), 'method' => 'delete']) }}
                                <button class="btn-sm btn-outline-danger"
                                        onclick=OnClickNextPage(event) {{strtoupper($category->name) == 'SALARY' || strtoupper($category->name) == 'ALLOWANCE' || strtoupper($category->name) == 'LUNCH' || strtoupper($category->name) == 'CREDITED/ADJUSTED' ? 'disabled' : ''}}>
                                    Delete
                                </button>
                                {{ Form::close() }}
                            @endif
                        </td>

                    <?php $i++;?>
                @empty
                    <tr>No Categories Found</tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
