<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Letter <a class="btn-sm btn-outline-primary" href="{{route('letter.download', $letter)}}" target="_blank">Print</a>
        </h2>
    </x-slot>

    @include('letter.partials._letter')

</x-app-layout>


