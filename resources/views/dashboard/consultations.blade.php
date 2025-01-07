@php
    $action_icons = [
        "icon:chat-bubble-bottom-center-text | click:sendMessage('{first_name}', '{last_name}', '{email}')",
        "icon:pencil | color:green | click:redirect('/consultations/{uuid}')",
        "icon:trash | color:red | click:deleteUser('{id}', '{first_name}','{last_name}')",
    ];

    $data = json_encode($consultations->toArray());
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Consultations') }}
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
                <x-bladewind::table :data="$data" include_columns="first_name,last_name,email,phone, country"
                    paginated="true" page_size="5" show_row_numbers="true" show_total_pages="true"
                    pagination_style="numbers" :action_icons="$action_icons" searchable="true"
                    no_data_message="No consultations yet" />
            </div>
        </div>
    </div>

    <!-- send message modal -->
    <x-bladewind::modal name="send-message" title="" ok_button_action="sendEmail()" close_after_action="false"
        backdrop_can_close="false">
        <form method="post" action="{{ route('consultations.send-email') }}" class="email-form">
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
    </x-bladewind::modal>

    <!-- delete user modal -->
    <x-bladewind::modal name="delete-user" type="error" title="Confirm User Deletion" ok_button_action="doDolete()">
        Are you really sure you want to delete <b class="title"></b>?
        This action cannot be reversed.
        <form method="post" action="{{ route('consultations.destroy') }}" class="delete-form">
            @csrf
            @method('delete')
            <input type="hidden" name="user_id" value="" class="user_id">
        </form>
    </x-bladewind::modal>
    <script>
        sendMessage = (first_name, last_name, email) => {
            showModal('send-message');
            domEl('.bw-send-message .modal-title').innerText = `Send Message to ${first_name} ${last_name}`;
            domEl('.first-name').value = `${first_name}`;
            domEl('.last-name').value = `${last_name}`;
            domEl('.email').value = `${email}`;
        }

        deleteUser = (id, first_name, last_name) => {
            showModal('delete-user');
            domEl('.bw-delete-user .title').innerText = `${first_name} ${last_name}`;
            domEl('.user_id').value = `${id}`;
        }

        redirect = (url) => {
            window.open(url, '_self');
        }

        sendEmail = () => {
            if (validateForm('.email-form')) {
                domEl('.email-form').submit();
            } else {
                return false;
            }
        }

        doDolete = () => {
            if (validateForm('.delete-form')) {
                domEl('.delete-form').submit();
            } else {
                return false;
            }
        }
    </script>
</x-app-layout>
