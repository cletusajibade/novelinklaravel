@php
     $action_icons = [
        "icon:pencil | color:green | click:redirect('/payments/{id}')",
        "icon:trash | color:red | click:deletePayment('{id}')",
    ];

    $data = json_encode($payments->toArray());
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (session('success'))
                    <x-bladewind::alert type="success" shade="dark">
                        {{ session('success') }}
                    </x-bladewind::alert>
                @endif
                <x-bladewind::modal name="user-deleted" type="error" title="Confirm User Deletion"
                    ok_button_action="doDolete()">
                    Are you really sure you want to delete <b class="title"></b>?
                    This action cannot be reversed.
                </x-bladewind::modal>
                <x-bladewind::table :data="$data" include_columns="confirmation_no,client_id,stripe_payment_id,amount,status"
                    paginated="true" page_size="10" show_row_numbers="true" show_total_pages="true"
                    pagination_style="arrows" :action_icons="$action_icons" searchable="true"
                    no_data_message="No payments yet" />
            </div>
        </div>
    </div>

    {{-- <!-- send message modal -->
    <x-bladewind::modal name="send-message" title="" ok_button_action="sendEmail()" close_after_action="false"
        backdrop_can_close="false">
        <form method="post" action="{{ route('client.send-email') }}" class="email-form">
            @csrf
            <div class="mb-6">
                The message will be delivered to the registered email address.
            </div>
            <x-bladewind::textarea name="email_message" placeholder="Type message here..." rows="5"
                required="true" onfocus="changeCss('.email_message', '!border-2,!border-red-400')"
                onblur="changeCss('.email_message', '!border-2,!border-red-400', 'remove')" />
            <input type="hidden" name="first_name" value="" class="first-name">
            <input type="hidden" name="last_name" value="" class="last-name">
            <input type="hidden" name="email" value="" class="email">

        </form>
    </x-bladewind::modal> --}}

    <!-- delete user modal -->
    <x-bladewind::modal name="delete-user" type="error" title="Confirm User Deletion" ok_button_action="doDolete()">
        Are you really sure you want to delete <b class="title"></b>?
        This action cannot be reversed.
        <form method="post" action="{{ route('client.destroy') }}" class="delete-form">
            @csrf
            @method('delete')
            <input type="hidden" name="user_id" value="" class="user_id">
        </form>
    </x-bladewind::modal>
    <script>


        // redirect = (url) => {
        //     window.open(url, '_self');
        // }

        // doDolete = () => {
        //     if (validateForm('.delete-form')) {
        //         domEl('.delete-form').submit();
        //     } else {
        //         return false;
        //     }
        // }
    </script>
</x-app-layout>
