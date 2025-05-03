<x-layout>

    {{-- Hero Section --}}
    <section class="relative h-[500px] bg-cover bg-center flex items-center justify-center text-white" style="background-image: url('https://via.placeholder.com/1920x500.png?text=Hero+Background+Image');">
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black opacity-50"></div>
        {{-- Content --}}
        <div class="relative z-10 text-center px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Find Your Dream Home</h1>
            <p class="text-lg md:text-xl mb-8">Discover the perfect property for your needs</p>
            <a href="#search" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-md text-lg transition duration-300">
                Search properties
            </a>
        </div>
    </section>

    {{-- Search Bar Section --}}
    <section id="search" class="bg-white py-6 -mt-16 relative z-20 container mx-auto max-w-4xl rounded-lg shadow-lg px-6">
        <form action="#" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            {{-- Location --}}
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <input type="text" id="location" name="location" placeholder="City, neighborhood..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            {{-- Price --}}
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                <select id="price" name="price" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">Price Range</option>
                    <option value="0-1000">$0 - $1000</option>
                    <option value="1000-2000">$1000 - $2000</option>
                    <option value="2000-3000">$2000 - $3000</option>
                    <option value="3000+">$3000+</option>
                </select>
            </div>
            {{-- Property Type --}}
            <div>
                <label for="property_type" class="block text-sm font-medium text-gray-700 mb-1">Property Type</label>
                <select id="property_type" name="property_type" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">Property Type</option>
                    <option value="apartment">Apartment</option>
                    <option value="house">House</option>
                    <option value="condo">Condo</option>
                    <option value="studio">Studio</option>
                </select>
            </div>
            {{-- Search Button --}}
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md w-full md:w-auto">
                Search properties
            </button>
        </form>
    </section>

    {{-- Featured Properties Section --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Featured Properties</h2>
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">View All Properties</a>
            </div>

            {{-- Property Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Property Card 1 --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <img src="https://via.placeholder.com/400x250.png?text=Property+1" alt="Modern Luxury Apartment" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Modern Luxury Apartment</h3>
                        <p class="text-gray-600 text-sm mb-4">Downtown, New York</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">$2500</span>
                            <span class="text-gray-600 text-sm">per month</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 border-t pt-4 mb-4">
                            <span><i class="fas fa-bed mr-1"></i> 2 Bedrooms</span>
                            <span><i class="fas fa-bath mr-1"></i> 2 Bathrooms</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i> 1200 sq ft</span>
                        </div>
                        <a href="#" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center transition duration-300">
                            View Details
                        </a>
                    </div>
                </div>

                {{-- Property Card 2 --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <img src="https://via.placeholder.com/400x250.png?text=Property+2" alt="Cozy Studio in City Center" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Cozy Studio in City Center</h3>
                        <p class="text-gray-600 text-sm mb-4">Midtown, Chicago</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">$1800</span>
                            <span class="text-gray-600 text-sm">per month</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 border-t pt-4 mb-4">
                            <span><i class="fas fa-bed mr-1"></i> 1 Bedroom</span>
                            <span><i class="fas fa-bath mr-1"></i> 1 Bathroom</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i> 750 sq ft</span>
                        </div>
                        <a href="#" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center transition duration-300">
                            View Details
                        </a>
                    </div>
                </div>

                {{-- Property Card 3 --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <img src="https://via.placeholder.com/400x250.png?text=Property+3" alt="Spacious Family Home" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Spacious Family Home</h3>
                        <p class="text-gray-600 text-sm mb-4">Suburb, Boston</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">$3200</span>
                            <span class="text-gray-600 text-sm">per month</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 border-t pt-4 mb-4">
                            <span><i class="fas fa-bed mr-1"></i> 4 Bedrooms</span>
                            <span><i class="fas fa-bath mr-1"></i> 3 Bathrooms</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i> 2400 sq ft</span>
                        </div>
                        <a href="#" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center transition duration-300">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Choose Us Section --}}
    <section class="py-16 bg-blue-50">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Why Choose HouseRental?</h2>
            <p class="text-gray-600 mb-12 max-w-2xl mx-auto">We make finding your perfect home simple and stress-free.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Feature 1: Ideal Locations --}}
                <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
                    <div class="flex justify-center mb-4">
                        <div class="bg-blue-100 text-blue-600 rounded-full p-4 inline-flex">
                            {{-- Placeholder for Location Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Ideal Locations</h3>
                    <p class="text-gray-600 text-sm">Explore properties in prime locations across the city.</p>
                </div>

                {{-- Feature 2: Transparent Pricing --}}
                <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
                     <div class="flex justify-center mb-4">
                        <div class="bg-blue-100 text-blue-600 rounded-full p-4 inline-flex">
                            {{-- Placeholder for Pricing/Tag Icon --}}
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                             </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Transparent Pricing</h3>
                    <p class="text-gray-600 text-sm">No hidden fees or surprise costs, ever.</p>
                </div>

                {{-- Feature 3: Quality Verified --}}
                <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
                     <div class="flex justify-center mb-4">
                        <div class="bg-blue-100 text-blue-600 rounded-full p-4 inline-flex">
                            {{-- Placeholder for Check/Shield Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Quality Verified</h3>
                    <p class="text-gray-600 text-sm">All our listings are verified for quality and accuracy.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Add Font Awesome for icons if not already included --}}
    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

</x-layout>
