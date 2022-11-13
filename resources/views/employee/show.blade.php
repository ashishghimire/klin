<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{$employee->name}} Salary Data
        </h2>
    </x-slot>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <table id="salary-info" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Details</th>
                </tr>
                </thead>
                <tbody>
                @forelse($employee->salaries as $salary)
                    <tr>
                        <td>
                            {{$salary->nepaliDate}}
                        </td>
                        <td>
                            {{$salary->amount}}
                        </td>
                        <td>
                            {{$salary->type}}
                        </td>
                        <td>
                            {{$salary->details}}
                        </td>
                    </tr>
                @empty
                    No data available
                @endforelse
                </tbody>

            </table>
        </div>
    </div>
</x-app-layout>
