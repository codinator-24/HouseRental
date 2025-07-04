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
    <div id="pdf-content" class="bg-gray-50 min-h-screen py-8" x-data="{
        showConfirmModal: false,
        showSuccessModal: {{ session()->has('success') ? 'true' : 'false' }},
    
        initiateSignAgreement() {
            const paymentMethod = document.getElementById('payment_method').value;
            const rentAmountValue = document.getElementById('rent_amount').value;
    
            // Validate rent amount
            if (!rentAmountValue || parseFloat(rentAmountValue) < 0.50) {
                alert('Please enter a valid rent amount (minimum $0.50).');
                return;
            }
    
            if (paymentMethod === 'Credit') {
                this.showConfirmModal = true;
            } else if (paymentMethod === 'Cash') {
                const rentAmountValue = document.getElementById('rent_amount').value;
                if (!rentAmountValue || parseFloat(rentAmountValue) < 0.50) {
                    alert('Please enter a valid rent amount (minimum $0.50) before choosing cash payment.');
                    return;
                }
                this.$dispatch('open-cash-modal', { bookingId: {{ $booking->id }}, rentAmount: rentAmountValue });
            }
        },
    
        processSignAgreement() {
            // Get form values
            const rentAmountValue = document.getElementById('rent_amount').value;
            const rentFrequency = document.getElementById('rent_frequency').value;
            const paymentMethod = document.getElementById('payment_method').value;
            const notes = document.querySelector('textarea[name=\'notes\']').value;
    
            // Validate rent amount
            if (!rentAmountValue || parseFloat(rentAmountValue) < 0.50) {
                alert('Please enter a valid rent amount (minimum $0.50).');
                return;
            }
    
            // Get the checkout form
            const form = this.$refs.checkoutForm;
            const hiddenRentInput = form.querySelector('input[name=\'rent_amount_checkout\']');
    
            if (hiddenRentInput) {
                hiddenRentInput.value = rentAmountValue;
            } else {
                console.error('Hidden rent_amount_checkout input not found.');
                alert('An error occurred. Could not process rent amount.');
                return;
            }
    
            // Add additional hidden inputs for other form data
            this.addHiddenInput(form, 'rent_frequency', rentFrequency);
            this.addHiddenInput(form, 'payment_method', paymentMethod);
            this.addHiddenInput(form, 'notes', notes);
    
            // Submit the checkout form
            form.submit();
            this.showConfirmModal = false;
        },
    
        addHiddenInput(form, name, value) {
            // Check if input already exists
            let input = form.querySelector(`input[name=\'${name}\']`);
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                form.appendChild(input);
            }
            input.value = value;
        }
    }">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="gradient-bg rounded-2xl p-8 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">@lang('words.agreement_create_title')</h1>
                        <p class="text-blue-100">@lang('words.agreement_create_subtitle')</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                            <p class="text-sm font-medium">@lang('words.agreement_id_label')</p>
                            <p class="text-lg font-bold">
                                {{ $agreement ? '#' . $agreement->id : __('words.agreement_status_new') }}
                            </p>
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
                        <h2 class="text-xl font-semibold text-gray-800">@lang('words.tenant_info_title')</h2>
                    </div>

                    <div class="flex items-start mb-6">
                        <div
                            class="w-20 h-20 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 overflow-hidden border border-gray-200">
                            @if ($booking->tenant->picture)
                                <img src="{{ asset('storage/' . $booking->tenant->picture) }}"
                                    alt="{{ $booking->tenant->full_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="profile-placeholder w-full h-full flex items-center justify-center">
                                    <i class="fas fa-camera text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-gray-800">{{ $booking->tenant->full_name ?? 'N/A' }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $booking->tenant->email ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-blue-500 w-5 mr-3"></i>
                            <span class="text-gray-700">{{ $booking->tenant->first_phoneNumber ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-mobile-alt text-blue-500 w-5 mr-3"></i>
                            <span class="text-gray-700">{{ $booking->tenant->second_phoneNumber ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-blue-500 w-5 mr-3"></i>
                            <span class="text-gray-700">{{ $booking->tenant->address ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100">
                        @if ($booking->tenant->IdCard)
                            <img src="{{ asset('storage/' . $booking->tenant->IdCard) }}" alt="Tenant ID Card"
                                class="rounded-lg max-h-32 w-auto mx-auto">
                        @else
                            <div class="profile-placeholder h-24 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-id-card text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">@lang('words.id_card_not_provided')</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Landlord Information -->
                <div class="bg-white rounded-2xl p-6 card-shadow info-card">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-user-tie text-green-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">@lang('words.landlord_info_title')</h2>
                    </div>

                    <div class="flex items-start mb-6">
                        <div
                            class="w-20 h-20 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 overflow-hidden border border-gray-200">
                            @if ($booking->house->landlord->picture)
                                <img src="{{ asset('storage/' . $booking->house->landlord->picture) }}"
                                    alt="{{ $booking->house->landlord->full_name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="profile-placeholder w-full h-full flex items-center justify-center">
                                    <i class="fas fa-camera text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-gray-800">
                                {{ $booking->house->landlord->full_name ?? 'N/A' }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $booking->house->landlord->email ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-green-500 w-5 mr-3"></i>
                            <span
                                class="text-gray-700">{{ $booking->house->landlord->first_phoneNumber ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-mobile-alt text-green-500 w-5 mr-3"></i>
                            <span
                                class="text-gray-700">{{ $booking->house->landlord->second_phoneNumber ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-green-500 w-5 mr-3"></i>
                            <span class="text-gray-700">{{ $booking->house->landlord->address ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100">
                        @if ($booking->house->landlord->IdCard)
                            <img src="{{ asset('storage/' . $booking->house->landlord->IdCard) }}"
                                alt="Landlord ID Card" class="rounded-lg max-h-32 w-auto mx-auto">
                        @else
                            <div class="profile-placeholder h-24 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-id-card text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">@lang('words.id_card_not_provided')</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Property Information -->
            <div class="bg-white rounded-2xl p-6 card-shadow mb-8 info-card">
                <div class="flex items-center mb-6">
                    <div class="bg-purple-100 rounded-full p-3 mr-4">
                        <i class="fas fa-home text-purple-600 text-xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">@lang('words.property_info_title')</h2>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">@lang('words.property_name_label'):</span>
                            <span class="font-semibold text-gray-800">{{ $booking->house->title ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">@lang('words.property_type_label'):</span>
                            <span
                                class="font-semibold text-gray-800">{{ $booking->house->property_type ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">@lang('words.property_address_label'):</span>
                            <span class="font-semibold text-gray-800">{{ $booking->house->first_address ?? '' }}
                                {{ $booking->house->second_address ?? '' }},
                                {{ $booking->house->city ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">@lang('words.number_of_rooms_label'):</span>
                            <span
                                class="font-semibold text-gray-800">{{ $booking->house->floors->sum('num_room') ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">@lang('words.number_of_floors_label'):</span>
                            <span
                                class="font-semibold text-gray-800">{{ $booking->house->floors->count() ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">@lang('words.square_footage_label'):</span>
                            <span class="font-semibold text-gray-800">{{ $booking->house->square_footage ?? 'N/A' }}
                                m²</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agreement Details -->
            <div class="bg-white rounded-2xl p-6 card-shadow mb-8 info-card">
                <x-commission-info />
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="bg-orange-100 rounded-full p-3 mr-4">
                            <i class="fas fa-file-contract text-orange-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">@lang('words.agreement_info_title')</h2>
                    </div>
                    @if ($agreement)
                        @if ($agreement->status === 'pending')
                            <div
                                class="status-badge bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-medium text-sm">
                                <i class="fas fa-clock mr-2"></i>@lang('words.agreement_status_pending')
                            </div>
                        @elseif($agreement->status === 'active' || $agreement->status === 'agreed')
                            <div
                                class="status-badge bg-green-100 text-green-800 px-4 py-2 rounded-full font-medium text-sm">
                                <i class="fas fa-check-circle mr-2"></i>@lang('words.agreement_status_active')
                            </div>
                        @else
                            {{-- Display other statuses or a default --}}
                            <div
                                class="status-badge bg-gray-100 text-gray-800 px-4 py-2 rounded-full font-medium text-sm">
                                @lang('words.agreement_status_label'): {{ ucfirst($agreement->status) }}
                            </div>
                        @endif
                    @else
                        <div class="status-badge bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-medium text-sm">
                            <i class="fas fa-plus-circle mr-2"></i>@lang('words.agreement_create_title')
                        </div>
                    @endif
                </div>

                <div class="grid md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-calendar-plus text-blue-600 mr-2"></i>
                            <span class="text-sm font-medium text-blue-800">@lang('words.signed_date_label')</span>
                        </div>
                        <p class="text-xl font-bold text-blue-900">{{ $signedDate->format('d/m/Y') }}</p>
                    </div>

                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-calendar-times text-red-600 mr-2"></i>
                            <span class="text-sm font-medium text-red-800">@lang('words.expires_date_label')</span>
                        </div>
                        <p class="text-xl font-bold text-red-900">{{ $expiresDate->format('d/m/Y') }}</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                            <label for="rent_amount"
                                class="text-sm font-medium text-green-800">@lang('words.monthly_rent_label')</label>
                        </div>
                        <input type="number" id="rent_amount" name="rent_amount"
                            value="{{ $booking->house->rent_amount ?? '0.00' }}"
                            class="text-xl font-bold text-green-900 bg-transparent border-b-2 border-green-200 focus:border-green-500 outline-none w-full"
                            step="0.01" min="0.50" required>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <label for="rent_frequency" class="text-gray-600">@lang('words.rent_frequency_label'):</label>
                            <select id="rent_frequency" name="rent_frequency"
                                class="font-semibold text-gray-800 border border-gray-300 rounded-md p-1 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="weekly">@lang('words.rent_frequency_weekly')</option>
                                <option value="monthly" selected>@lang('words.rent_frequency_monthly')</option>
                                <option value="yearly">@lang('words.rent_frequency_yearly')</option>
                            </select>
                        </div>
                        <div class="flex justify-between items-center">
                            <label for="payment_method" class="text-gray-600">@lang('words.payment_method_label'):</label>
                            <select id="payment_method" name="payment_method"
                                class="font-semibold text-gray-800 border border-gray-300 rounded-md p-1 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Credit">@lang('words.payment_method_credit')</option>
                                <option value="Cash">@lang('words.payment_method_cash')</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">@lang('words.payment_date_label'):</span>
                            <span class="font-semibold text-gray-800">{{ $signedDate->format('d/m/Y') }}
                                @lang('words.payment_date_value')</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">@lang('words.duration_label'):</span>
                            <span class="font-semibold text-gray-800">{{ $booking->month_duration }}
                                {{ $booking->month_duration > 1 ? __('words.duration_month_plural') : __('words.duration_month_singular') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-sticky-note text-blue-600 mr-3 mt-1"></i>
                            <div class="w-full">
                                <h4 class="font-semibold text-blue-900 mb-2">@lang('words.additional_notes_label')</h4>
                                <textarea name="notes" rows="3"
                                    class="w-full p-2 border border-blue-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-blue-800"
                                    placeholder="@lang('words.agreement_notes_placeholder')">@lang('words.agreement_notes_default', ['address' => ($booking->house->first_address ?? '') . ' ' . ($booking->house->second_address ?? '') . ', ' . ($booking->house->city ?? 'N/A'), 'duration' => $booking->month_duration])</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-10 flex justify-end space-x-4">
                <button onclick="downloadPDFAgreement()"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-150 ease-in-out">
                    <i class="fas fa-download mr-2"></i>@lang('words.download_pdf_button')
                </button>

                @if ($agreement->status === 'pending')
                    <button type="button" @click="initiateSignAgreement()"
                        class="gradient-bg text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-150 ease-in-out">
                        <i class="fas fa-file-signature mr-2"></i>@lang('words.sign_agreement_button')
                    </button>
                @endif
            </div>

            <!-- Modal of Credit Card -->
            <div x-show="showConfirmModal"
                class="fixed inset-0 z-[100] flex items-center justify-center backdrop-blur-sm bg-opacity-50 p-4"
                style="display: none;" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">

                <div @click.outside="showConfirmModal = false"
                    class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md transform transition-all"
                    x-show="showConfirmModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <div class="flex items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-file-signature text-blue-600 text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                @lang('words.confirm_signing_title')
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    @lang('words.confirm_signing_message')
                                </p>
                                <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                    <p class="text-sm text-blue-800">
                                        <strong>@lang('words.rent_amount_label'):</strong> $<span
                                            x-text="document.getElementById('rent_amount')?.value || '0.00'"></span><br>
                                        <strong>@lang('words.duration_label'):</strong> {{ $booking->month_duration }}
                                        {{ $booking->month_duration > 1 ? __('words.duration_month_plural') : __('words.duration_month_singular') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse space-y-2 sm:space-y-0 sm:space-x-3 sm:space-x-reverse">
                        <form x-ref="checkoutForm" action="{{ route('checkout') }}" method="POST"
                            class="w-full sm:w-auto">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <input type="hidden" name="rent_amount_checkout" value="">

                            <button @click="processSignAgreement()" type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                                <i class="fas fa-credit-card mr-2"></i>
                                @lang('words.sign_and_pay_button')
                            </button>
                        </form>

                        <button @click="showConfirmModal = false" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                            @lang('words.cancel_button')
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal of Cash -->
            <div x-data="{ show: false, bookingId: null, rentAmount: null }"
                x-on:open-cash-modal.window="show = true; bookingId = $event.detail.bookingId; rentAmount = $event.detail.rentAmount"
                x-show="show" x-cloak
                class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur-sm bg-opacity-50">
                <div @click.away="show = false" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                    class="bg-white rounded-2xl shadow-2xl p-8 max-w-3xl w-full mx-4 sm:mx-auto"
                    style="min-width: 400px;">
                    <h2 class="text-2xl font-bold mb-6 text-indigo-700">@lang('words.cash_appointment_title')</h2>

                    <form method="POST" action="{{ route('cash.appointment') }}">
                        @csrf
                        <input type="hidden" name="booking_id" :value="bookingId">
                        <input type="hidden" name="rent_amount" :value="rentAmount">

                        <div class="mt-4">
                            <label for="payment_deadline"
                                class="block text-sm font-medium text-gray-700">@lang('words.payment_deadline')</label>
                            <input type="date" name="payment_deadline" id="payment_deadline"
                                class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                        </div>

                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                @lang('words.cash_appointment_message')
                            </p>
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <strong>@lang('words.rent_amount_label'):</strong> $<span
                                        x-text="rentAmount || '0.00'"></span><br>
                                    <strong>@lang('words.duration_label'):</strong> {{ $booking->month_duration }}
                                    {{ $booking->month_duration > 1 ? __('words.duration_month_plural') : __('words.duration_month_singular') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 mt-6">
                            <button type="button" @click="show = false"
                                class="px-6 py-2 rounded-lg bg-gray-300 text-gray-800 font-semibold hover:bg-gray-400 transition">
                                @lang('words.cancel_button')
                            </button>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                                @lang('words.submit_button')
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Success Modal -->
            <div x-show="showSuccessModal" x-cloak
                class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur-sm bg-opacity-50">
                <div @click.away="showSuccessModal = false"
                    class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 sm:mx-auto text-center">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10 mb-4">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">@lang('words.success')</h2>
                    @if (session('success'))
                        <p class="text-gray-600 mb-2">{{ session('success') }}</p>
                    @endif
                    <button @click="showSuccessModal = false"
                        class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition mt-4">
                        @lang('words.ok_button')
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center py-8 mt-4">
                <p class="text-gray-500 text-sm">@lang('words.footer_copyright', ['year' => date('Y')])
                </p>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var today = new Date().toISOString().split('T')[0];
            document.getElementById('payment_deadline').setAttribute('min', today);
        });

        function downloadPDFAgreement() {
            const element = document.getElementById('pdf-content');
            const opt = {
                margin: 0.5,
                filename: 'agreement-{{ $agreement->id }}.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };
            html2pdf().from(element).set(opt).save();
        }
    </script>
</x-layout>
