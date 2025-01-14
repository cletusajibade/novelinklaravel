<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-lg mx-auto sticky top-0 z-10 flex justify-center py-4" style="background: #231f20;">
        <a href="{{ route('home') }}"> <img src="{{ asset('uploads/2020/08/novelink.png') }}" alt="Logo"
                width="160" /> </a>
    </div>
    <div class="max-w-lg mx-auto mt-0 mb-5 bg-white shadow-lg rounded-bl-lg rounded-br-lg p-6">
        <h1 class="text-2xl font-bold text-center text-gray-700 mb-6">Consultation Form</h1>
        <form action="{{ route('consultation.store') }}" method="post">
            @csrf
            <div class="mb-4">
                <label for="first_name" class="block text-gray-600 mb-2 text-sm">First Name *</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('first_name')
                    <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="last_name" class="block text-gray-600 mb-2 text-sm">Last Name *</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('last_name')
                    <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-600 mb-2 text-sm">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                @enderror
            </div>

            {{-- TODO: do proper phone validation using a laravel package --}}
            <div class="mb-4">
                <label for="phone" class="block text-gray-600 mb-2 text-sm">Phone Number *</label>
                <input type="tel" onkeypress="return /[0-9+ ]/i.test(event.key)" id="phone" name="phone"
                    value="{{ old('phone') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('phone')
                    <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="date_of_birth" class="block text-gray-600 mb-2 text-sm">Date of Birth *</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('date_of_birth')
                    <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="country" class="block text-gray-600 mb-2 text-sm">Country of Citizenship
                    *</label>
                <select id="country" name="country" value="{{ old('country') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @include('partials.countries', ['select_name_attr' => 'country'])
                </select>
                @error('country')
                    <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="country_of_residence" class="block text-gray-600 mb-2 text-sm">Country of Residence
                    *</label>
                <select id="country_of_residence" name="country_of_residence" value="{{ old('country_of_residence') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @include('partials.countries', ['select_name_attr' => 'country_of_residence'])
                </select>
                @error('country_of_residence')
                    <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-600 mb-2 text-sm">Select your Consultation Package(s): *</label>
                <div class="flex flex-col space-y-2 border border-gray-300 rounded-lg p-3">

                    @foreach ($packages as $package)
                        <label class="flex items-center space-x-2 text-sm">
                            <input type="checkbox" name="consultation_package[]" value="{{ $package->id }}"
                                {{ in_array($package->id, old('consultation_package', [])) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span>{{ $package->package_name . ' (' }}{{ fmod($package->amount, 1) == 0 ? number_format($package->amount, 0) : number_format($package->amount, 2) }}
                                {{ $package->currency . ')' }}</span>
                        </label>
                    @endforeach

                </div>
                @error('consultation_package')
                    <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Continue</button>
        </form>
    </div>
    <script>
        // scroll down to the first error message
        document.addEventListener('DOMContentLoaded', function() {
            const firstError = document.querySelector('.error-message');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    </script>
</body>

</html>
