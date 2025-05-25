{{-- C Style --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
    }

    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-shadow {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .profile-placeholder {
        background: linear-gradient(45deg, #f3f4f6, #e5e7eb);
        border: 2px dashed #d1d5db;
    }

    .status-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.8;
        }
    }

    .info-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }
</style>
<x-layout>
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="gradient-bg rounded-2xl p-8 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Rental Agreement</h1>
                        <p class="text-blue-100">Professional Property Rental Contract</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                            <p class="text-sm font-medium">Agreement ID</p>
                            <p class="text-lg font-bold">#RA-2025-001</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tenant and Landlord Information -->
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <!-- Tenant Information -->
                <div class="bg-white rounded-2xl p-6 card-shadow info-card">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 rounded-full p-3 mr-4">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Tenant Information</h2>
                    </div>

                    <div class="flex items-start mb-6">
                        <div
                            class="profile-placeholder w-20 h-20 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-camera text-gray-400 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-gray-800">Dyari Morison</h3>
                            <p class="text-gray-600 text-sm mb-2">dyari@gmail.com</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-blue-500 w-5 mr-3"></i>
                            <span class="text-gray-700">07701559713</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-mobile-alt text-blue-500 w-5 mr-3"></i>
                            <span class="text-gray-700">0771213402</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-blue-500 w-5 mr-3"></i>
                            <span class="text-gray-700">Sulaimany</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="profile-placeholder h-24 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-id-card text-gray-400 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">ID Card</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Landlord Information -->
                <div class="bg-white rounded-2xl p-6 card-shadow info-card">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-user-tie text-green-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Landlord Information</h2>
                    </div>

                    <div class="flex items-start mb-6">
                        <div
                            class="profile-placeholder w-20 h-20 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-camera text-gray-400 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-gray-800">Hallo Man</h3>
                            <p class="text-gray-600 text-sm mb-2">hallo@gmail.com</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-green-500 w-5 mr-3"></i>
                            <span class="text-gray-700">077504325678</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-mobile-alt text-green-500 w-5 mr-3"></i>
                            <span class="text-gray-700">07501541890</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-green-500 w-5 mr-3"></i>
                            <span class="text-gray-700">Sulaimany</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="profile-placeholder h-24 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-id-card text-gray-400 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">ID Card</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Information -->
            <div class="bg-white rounded-2xl p-6 card-shadow mb-8 info-card">
                <div class="flex items-center mb-6">
                    <div class="bg-purple-100 rounded-full p-3 mr-4">
                        <i class="fas fa-home text-purple-600 text-xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Property Information</h2>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Property Name:</span>
                            <span class="font-semibold text-gray-800">MyHouse</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Property Type:</span>
                            <span class="font-semibold text-gray-800">House</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Property Address:</span>
                            <span class="font-semibold text-gray-800">Sulaimany</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Number of Rooms:</span>
                            <span class="font-semibold text-gray-800">5</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Number of Floors:</span>
                            <span class="font-semibold text-gray-800">1</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Square Footage:</span>
                            <span class="font-semibold text-gray-800">200</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agreement Details -->
            <div class="bg-white rounded-2xl p-6 card-shadow mb-8 info-card">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="bg-orange-100 rounded-full p-3 mr-4">
                            <i class="fas fa-file-contract text-orange-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Agreement Information</h2>
                    </div>
                    <div class="status-badge bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-medium text-sm">
                        <i class="fas fa-clock mr-2"></i>Pending
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-calendar-plus text-blue-600 mr-2"></i>
                            <span class="text-sm font-medium text-blue-800">Signed Date</span>
                        </div>
                        <p class="text-xl font-bold text-blue-900">15/5/2025</p>
                    </div>

                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-calendar-times text-red-600 mr-2"></i>
                            <span class="text-sm font-medium text-red-800">Expires Date</span>
                        </div>
                        <p class="text-xl font-bold text-red-900">15/7/2025</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                            <span class="text-sm font-medium text-green-800">Monthly Rent</span>
                        </div>
                        <p class="text-xl font-bold text-green-900">$150</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Rent Frequency:</span>
                            <span class="font-semibold text-gray-800">Monthly</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Payment Method:</span>
                            <span class="font-semibold text-gray-800">Credit</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Payment Date:</span>
                            <span class="font-semibold text-gray-800">15/5/2025</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-semibold text-gray-800">2 Months</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-sticky-note text-blue-600 mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-2">Additional Notes</h4>
                                <p class="text-blue-800">This Agreement for 2 Months.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center py-8">
                <p class="text-gray-500 text-sm">© 2025 Rental Agreement System. All rights reserved.</p>
            </div>
        </div>
    </div>
</x-layout>

{{-- G Style --}}
{{-- <style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f3f4f6;
        /* Light gray background */
    }

    .card {
        background-color: white;
        border-radius: 0.75rem;
        /* 12px */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        /* 24px */
        margin-bottom: 1.5rem;
        /* 24px */
    }

    .section-title {
        font-size: 1.25rem;
        /* 20px */
        font-weight: 600;
        color: #1f2937;
        /* Dark gray */
        margin-bottom: 1rem;
        /* 16px */
        border-bottom: 1px solid #e5e7eb;
        /* Light gray border */
        padding-bottom: 0.5rem;
        /* 8px */
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        /* 8px top/bottom */
        border-bottom: 1px solid #f3f4f6;
        /* Very light gray border for items */
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 500;
        color: #4b5563;
        /* Medium gray */
    }

    .info-value {
        color: #1f2937;
        /* Dark gray */
        text-align: right;
    }

    .status-pending {
        background-color: #fef3c7;
        /* Light yellow */
        color: #92400e;
        /* Dark yellow/brown */
        padding: 0.25rem 0.75rem;
        /* 4px 12px */
        border-radius: 9999px;
        /* Pill shape */
        font-size: 0.875rem;
        /* 14px */
        font-weight: 500;
    }

    .image-placeholder {
        width: 100px;
        height: 100px;
        background-color: #e5e7eb;
        /* Light gray */
        border-radius: 50%;
        /* 8px */
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        /* Gray */
        font-size: 0.875rem;
        /* 14px */
        margin-bottom: 0.5rem;
        /* 8px */
    }

    .IdCard_image-placeholder {
        width: 210px;
        height: 120px;
        background-color: #e5e7eb;
        /* Light gray */
        border-radius: 0.5rem;
        /* 8px */
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        /* Gray */
        font-size: 0.875rem;
        /* 14px */
        margin-bottom: 0.5rem;
        /* 8px */
    }

    .header-main {
        background-color: #4f46e5;
        /* Indigo */
        color: white;
        padding: 2rem 0;
        /* 32px */
        text-align: center;
        border-bottom-left-radius: 1.5rem;
        /* 24px */
        border-bottom-right-radius: 1.5rem;
        /* 24px */
        margin-bottom: 2rem;
        /* 32px */
    }

    .header-main h1 {
        font-size: 2.25rem;
        /* 36px */
        font-weight: 700;
    }

    /* Additional styling for better visual separation and emphasis */
    .highlight-section {
        border-left: 4px solid #4f46e5;
        /* Indigo border */
        padding-left: 1rem;
        /* 16px */
    }
</style>
<x-layout>
    <header class="header-main">
        <h1>Rental Agreement Details</h1>
    </header>

    <div class="container mx-auto p-4 md:p-8 max-w-4xl">

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="card">
                <h2 class="section-title">Tenant Information</h2>
                <div class="flex flex-col items-center md:items-start mb-4">
                    <div class="image-placeholder">Photo</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Name:</span>
                    <span class="info-value">Hallo Man</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Primary Phone:</span>
                    <span class="info-value">077504325678</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Secondary Phone:</span>
                    <span class="info-value">07501541890</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">hallo@gmail.com</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Address:</span>
                    <span class="info-value">Sulaimany</span>
                </div>
                <div class="mt-4 flex flex-col items-center md:items-start">
                    <div class="IdCard_image-placeholder">ID Card</div>
                </div>
            </div>

            <div class="card">
                <h2 class="section-title">Landlord Information</h2>
                <div class="flex flex-col items-center md:items-start mb-4">
                    <div class="image-placeholder">Photo</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Name:</span>
                    <span class="info-value">Dyari Morison</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Primary Phone:</span>
                    <span class="info-value">07701559713</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Secondary Phone:</span>
                    <span class="info-value">0771213402</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">dyari@gmail.com</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Address:</span>
                    <span class="info-value">Sulaimany</span>
                </div>
                <div class="mt-4 flex flex-col items-center md:items-start">
                    <div class="IdCard_image-placeholder">ID Card</div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="section-title">Property Information</h2>
            <div class="grid md:grid-cols-2 gap-x-6">
                <div class="info-item">
                    <span class="info-label">Property Name:</span>
                    <span class="info-value">[Not Specified in PDF]</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Property Type:</span>
                    <span class="info-value">[Not Specified in PDF]</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Square Footage:</span>
                    <span class="info-value">[Not Specified in PDF]</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Number of Floors:</span>
                    <span class="info-value">[Not Specified in PDF]</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Number of Rooms:</span>
                    <span class="info-value">[Not Specified in PDF]</span>
                </div>
                <div class="info-item md:col-span-2">
                    <span class="info-label">Property Address:</span>
                    <span class="info-value">[Not Specified in PDF]</span>
                </div>
            </div>
        </div>

        <div class="card highlight-section">
            <h2 class="section-title">Agreement Information</h2>
            <div class="flex justify-between items-center mb-4">
                <span class="info-label text-lg">Status:</span>
                <span class="status-pending">Pending</span>
            </div>
            <div class="grid md:grid-cols-2 gap-x-6">
                <div class="info-item">
                    <span class="info-label">Signed At:</span>
                    <span class="info-value">15/05/2025</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Rent Amount:</span>
                    <span class="info-value">$150</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Expires At:</span>
                    <span class="info-value">15/07/2025</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Rent Frequency:</span>
                    <span class="info-value">Monthly</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value">Credit</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Paid At:</span>
                    <span class="info-value">15/05/2025</span>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-2">Notes:</h3>
                <p class="text-gray-600 italic">This Agreement is for 2 Months.</p>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <button
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg shadow transition duration-150 ease-in-out">
                Download PDF
            </button>
            <button
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow transition duration-150 ease-in-out">
                Sign Agreement
            </button>
        </div>

    </div>

    <footer class="text-center p-4 mt-8 text-sm text-gray-500">
        <p>&copy; <span id="currentYear"></span> Your Company Name. All rights reserved.</p>
    </footer>

    <script>
        // Script to set the current year in the footer
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>

</x-layout> --}}
