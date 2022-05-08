<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{$employee->name}} Salary Data
        </h2>
    </x-slot>
    <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <table id="salary-info" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>Date</th>
                <th>Amount</th>
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
                </tr>
            @empty
                No Salary data available
            @endforelse
            </tbody>

        </table>
    </div>
</x-app-layout>
