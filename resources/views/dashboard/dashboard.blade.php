<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg mb-7">
                <div class="grid grid-cols-3 gap-5 mb-7">
                    <x-bladewind::statistic icon_position="right" number="{{ $consultationsCount }}" label="Total Clients" class="border-gray-200">
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
                    <x-bladewind::statistic icon_position="right" number="{{ $totalPayments }}" label="Total payments" class="border-gray-200">
                        <x-slot name="icon">
                            <x-bladewind::icon name="currency-dollar"
                                class="!h-14 !w-14 !text-white bg-orange-400 p-4 rounded-full cursor-pointer hover:bg-orange-500 hover:p-3" />
                        </x-slot>
                    </x-bladewind::statistic>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                charts
            </div>
        </div>
    </div>
</x-app-layout>
