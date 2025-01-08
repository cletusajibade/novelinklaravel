<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Consultation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if ($consultation)
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ $consultation->first_name . ' ' . $consultation->last_name }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __("Update client's information") }}
                                </p>
                            </header>

                            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                @csrf
                            </form>

                            <form method="post" action="{{ route('consultations.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('patch')

                                <div>
                                    <x-input-label for="first_name" :value="__('First Name')" />
                                    <x-text-input id="first_name" name="first_name" type="text"
                                        class="mt-1 block w-full" :value="old('first_name', $consultation->first_name)" required autofocus
                                        autocomplete="first_name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                                </div>

                                <div>
                                    <x-input-label for="last_name" :value="__('Last Name')" />
                                    <x-text-input id="last_name" name="last_name" type="text"
                                        class="mt-1 block w-full" :value="old('last_name', $consultation->last_name)" required autofocus
                                        autocomplete="last_name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                        :value="old('email', $consultation->email)" required autocomplete="username" />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                    @if ($consultation instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$consultation->hasVerifiedEmail())
                                        <div>
                                            <p class="text-sm mt-2 text-gray-800">
                                                {{ __('Your email address is unverified.') }}

                                                <button form="send-verification"
                                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    {{ __('Click here to re-send the verification email.') }}
                                                </button>
                                            </p>

                                            @if (session('status') === 'verification-link-sent')
                                                <p class="mt-2 font-medium text-sm text-green-600">
                                                    {{ __('A new verification link has been sent to your email address.') }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <x-input-label for="phone" :value="__('Phone')" />
                                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                                        :value="old('phone', $consultation->phone)" required autofocus autocomplete="phone" />
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>

                                <div>
                                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                    <x-text-input id="date_of_birth" name="date_of_birth" type="date"
                                        class="mt-1 block w-full" :value="old('date_of_birth', $consultation->date_of_birth)" required autofocus
                                        autocomplete="date_of_birth" />
                                    <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                                </div>

                                <div>
                                    <x-input-label for="country" :value="__('Country')" />
                                    <x-text-input id="country" name="country" type="text" class="mt-1 block w-full"
                                        :value="old('country', $consultation->country)" required autofocus autocomplete="country" />
                                    <x-input-error class="mt-2" :messages="$errors->get('country')" />
                                </div>

                                <div>
                                    <x-input-label for="country_of_residence" :value="__('Country of Residence')" />
                                    <x-text-input id="country_of_residence" name="country_of_residence" type="text"
                                        class="mt-1 block w-full" :value="old('country_of_residence', $consultation->country_of_residence)" required autofocus
                                        autocomplete="country_of_residence" />
                                    <x-input-error class="mt-2" :messages="$errors->get('country_of_residence')" />
                                </div>

                                <div>
                                    <x-input-label for="country_residence_status" :value="__('Country of Residence Status')" />
                                    <x-text-input id="country_residence_status" name="country_residence_status"
                                        type="text" class="mt-1 block w-full" :value="old(
                                            'country_residence_status',
                                            $consultation->country_residence_status,
                                        )" autofocus
                                        autocomplete="country_residence_status" />
                                    <x-input-error class="mt-2" :messages="$errors->get('country_residence_status')" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                                    @if (session('status') === 'profile-updated')
                                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </section>
                    @else
                        <p>No record found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
