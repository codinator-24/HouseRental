<x-layout>

    @guest
        {{-- Link prompting guests to log in --}}
        <div class="max-w-xl mx-auto mb-6 shadow rounded-md bg-white">
            <a href="{{ route('login') }}" class="block text-center text-lg font-medium text-gray-700 hover:bg-gray-50 py-3 px-4 rounded-md transition duration-150 ease-in-out">
                Please login to put your property up for rent</a>
        </div>
    @endguest


    @auth
        {{-- Welcome message for authenticated users --}}
        <div class="max-w-xl mx-auto mb-6 shadow rounded-md bg-white">
            <h1 class="text-center text-lg font-medium text-gray-700 py-3 px-4">
            Welcome {{ auth()->user()->user_name }}, Please click here to put your property up for rent</h1>
        </div>
    @endauth

    {{-- @guest --}}
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">@lang('words.Our_Services')</h1>

        <!-- Grid Container -->
        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card 1 -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-semibold mb-3">Card Title 1</h2>
                <p class="text-gray-700">
                    This is the content for the first card. It provides a brief description or some information.
                </p>
                @auth
                    <button class="mt-4 bg-blue-800 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Learn More
                    </button>
                @endauth
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-semibold mb-3">Card Title 2</h2>
                <p class="text-gray-700">
                    Content for the second card goes here. Tailor it to your needs.
                </p>
                @auth
                    <button class="mt-4 bg-blue-800 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Learn More
                    </button>
                @endauth
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-semibold mb-3">Card Title 3</h2>
                <p class="text-gray-700">
                    Here is the description for the third card. Keep it concise and informative.
                </p>
                @auth
                    <button class="mt-4 bg-blue-800 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Learn More
                    </button>
                @endauth
            </div>

            <!-- Card 4 -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-semibold mb-3">Card Title 4</h2>
                <p class="text-gray-700">
                    Finally, the content for the fourth card. You can add images, links, etc.
                </p>
                @auth
                    <button class="mt-4 bg-blue-800 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Learn More
                    </button>
                @endauth
            </div>

        </div>

    </div>
    {{-- @endguest --}}
</x-layout>
