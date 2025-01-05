<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div> --}}

                <table class="table" data-paging="true" data-filtering="true" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th data-breakpoints="xs">ID</th>
                            {{-- <th>UUID</th> --}}
                            <th data-title="F.Name">First Name</th>
                            <th data-title="L.Name">Last Name</th>
                            <th data-breakpoints="xs">Email</th>
                            <th data-breakpoints="xs sm">Phone</th>
                            <th data-breakpoints="xs sm md" data-title="DoB">Date of Birth</th>
                            <th data-breakpoints="xs sm md">Country</th>
                            <th data-breakpoints="xs sm md" data-title="CoR">Country of Residence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($consultations as $consultation)
                            <tr>
                                <td>{{ $consultation->id }}</td>
                                {{-- <td>{{ $consultation->uuid }}</td> --}}
                                <td>{{ $consultation->first_name }}</td>
                                <td>{{ $consultation->last_name }}</td>
                                <td>{{ $consultation->email }}</td>
                                <td>{{ $consultation->phone }}</td>
                                <td>{{ $consultation->date_of_birth }}</td>
                                <td>{{ $consultation->country }}</td>
                                <td>{{ $consultation->country_of_residence }}</td>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</x-app-layout>
