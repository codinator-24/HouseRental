<x-adminLayout> {{-- Assuming you have an admin layout component --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('User Communications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <h3 class="mb-4 text-lg font-semibold">Agreement-Based Message Threads</h3>
                    @if (count($agreementThreadDetails) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped custom-table">
                                <thead>
                                    <tr>
                                        <th>Agreement ID</th>
                                        <th>House</th>
                                        <th>Tenant</th>
                                        <th>Landlord</th>
                                        <th>Last Message</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agreementThreadDetails as $thread)
                                        <tr>
                                            <td>{{ $thread->id }}</td>
                                            <td>{{ $thread->house_title }}</td>
                                            <td>{{ $thread->participant1 }}</td>
                                            <td>{{ $thread->participant2 }}</td>
                                            <td class="text-sm text-muted">{{ \Carbon\Carbon::parse($thread->last_message_at)->diffForHumans() }}</td>
                                            <td class="text-right">
                                                <a href="{{ $thread->link }}" class="btn btn-sm btn-outline-primary">View Thread</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $agreementThreads->links() }}
                        </div>
                    @else
                        <p>No agreement-based message threads found.</p>
                    @endif

                    <hr class="my-8">

                    <h3 class="mb-4 text-lg font-semibold">Inquiry-Based Message Threads</h3>
                    @if (count($inquiryThreadDetails) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped custom-table">
                                <thead>
                                    <tr>
                                        <th>House</th>
                                        <th>Participant 1</th>
                                        <th>Participant 2</th>
                                        <th>Last Message</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inquiryThreadDetails as $thread)
                                        <tr>
                                            <td>{{ $thread->house_title }}</td>
                                            <td>{{ $thread->participant1 }}</td>
                                            <td>{{ $thread->participant2 }}</td>
                                            <td class="text-sm text-muted">{{ \Carbon\Carbon::parse($thread->last_message_at)->diffForHumans() }}</td>
                                            <td class="text-right">
                                                <a href="{{ $thread->link }}" class="btn btn-sm btn-outline-primary">View Thread</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $inquiryThreads->links() }}
                        </div>
                    @else
                        <p>No inquiry-based message threads found.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-adminLayout>
