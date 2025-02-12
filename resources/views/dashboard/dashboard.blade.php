<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg mb-7">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-7">
                    <x-bladewind::statistic icon_position="right" number="{{ $consultationsCount }}" label="Total Clients"
                        class="border-gray-200">
                        <x-slot name="icon">
                            <x-bladewind::icon name="users"
                                class="!h-14 !w-14 !text-white bg-lime-500 p-4 rounded-full cursor-pointer hover:bg-lime-600 hover:p-3" />
                        </x-slot>
                    </x-bladewind::statistic>
                    <x-bladewind::statistic icon_position="right" number="{{ $consultationsCount }}"
                        label="Client Countries" class="border-gray-200">
                        <x-slot name="icon">
                            <x-bladewind::icon name="users"
                                class="!h-14 !w-14 !text-white bg-blue-400 p-4 rounded-full cursor-pointer hover:bg-blue-500 hover:p-3" />
                        </x-slot>
                    </x-bladewind::statistic>
                    <x-bladewind::statistic icon_position="right" number="{{ $appointmentsCount }}"
                        label="Total Appointments" class="border-gray-200">
                        <x-slot name="icon">
                            <x-bladewind::icon name="calendar-days"
                                class="!h-14 !w-14 !text-white bg-pink-400 p-4 rounded-full cursor-pointer hover:bg-pink-500 hover:p-3" />
                        </x-slot>
                    </x-bladewind::statistic>
                    <x-bladewind::statistic icon_position="right" number="{{ $totalPayments }}" label="Total payments"
                        class="border-gray-200">
                        <x-slot name="icon">
                            <x-bladewind::icon name="currency-dollar"
                                class="!h-14 !w-14 !text-white bg-orange-400 p-4 rounded-full cursor-pointer hover:bg-orange-500 hover:p-3" />
                        </x-slot>
                    </x-bladewind::statistic>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-6 bg-white rounded shadow-lg">
                    {!! $pie_chart->container() !!}
                </div>
                <div class="p-6 bg-white rounded shadow-xl">
                    {!! $bar_chart->container() !!}
                </div>
                {{-- <div class="p-6 bg-white rounded shadow-xl">
                    {!! $area_chart->container() !!}
                </div> --}}

            </div>
            <div class="flex justify-between items-center mb-1 mt-10">
                <h2 class="text-lg font-semibold">Client List</h2>
                <a href="{{ route('clients') }}" class="text-blue-500 hover:underline">View All</a>
            </div>
            <table class="min-w-full border bg-white border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border text-left w-14">ID</th>
                        <th class="px-4 py-2 border text-left">First Name</th>
                        <th class="px-4 py-2 border text-left">Last Name</th>
                        <th class="px-4 py-2 border text-left">Email</th>
                        <th class="px-4 py-2 border text-left">Phone</th>
                        <th class="px-4 py-2 border text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 border text-left w-14">{{ $client->id }}</td>
                            <td class="px-4 py-2 border text-left">{{ $client->first_name }}</td>
                            <td class="px-4 py-2 border text-left">{{ $client->last_name }}</td>
                            <td class="px-4 py-2 border text-left">{{ $client->email }}</td>
                            <td class="px-4 py-2 border text-left">{{ $client->phone }}</td>
                            <td class="px-4 py-2 border text-left">
                                <a href="{{ route('client.edit', $client->uuid) }}"
                                    class="px-2 py-1 mr-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('client.edit', $client->uuid) }}"
                                    class="px-2 py-1 mr-1 bg-green-500 text-white text-sm rounded hover:bg-green-600">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('client.destroy', $client->uuid) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600"
                                        onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <script src="{{ $pie_chart->cdn() }}"></script>
    {{ $pie_chart->script() }}
    {{ $bar_chart->script() }}
    {{-- {{ $area_chart->script() }} --}}

</x-app-layout>
