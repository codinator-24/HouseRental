   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agreement #{{ $agreement->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #333; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 40px; }
        .header img { width: 150px; }
        .header h1 { font-size: 24px; color: #4a5568; margin-top: 10px; }
        .section { margin-bottom: 30px; }
        .section-title { font-size: 18px; font-weight: bold; color: #2d3748; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 15px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .info-box { background-color: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; }
        .info-box h3 { font-size: 16px; font-weight: bold; color: #4a5568; margin-bottom: 10px; }
        .info-box p { margin: 0 0 5px; }
        .info-box strong { color: #2d3748; }
        .footer { text-align: center; margin-top: 50px; font-size: 10px; color: #718096; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo">
            <h1>@lang('words.agreement_create_title')</h1>
        </div>

        <div class="section">
            <h2 class="section-title">@lang('words.agreement_info_title')</h2>
            <div class="info-box">
                <p><strong>@lang('words.agreement_id_label'):</strong> #{{ $agreement->id }}</p>
                <p><strong>@lang('words.signed_date_label'):</strong> {{ $agreement->signed_at->format('F j, Y') }}</p>
                <p><strong>@lang('words.expires_date_label'):</strong> {{ $agreement->expires_at->format('F j, Y') }}</p>
                <p><strong>@lang('words.monthly_rent_label'):</strong> ${{ number_format($agreement->rent_amount, 2) }}</p>
                <p><strong>@lang('words.rent_frequency_label'):</strong> {{ ucfirst($agreement->rent_frequency) }}</p>
            </div>
        </div>

        <div class="section info-grid">
            <div class="info-box">
                <h3>@lang('words.tenant_info_title')</h3>
                <p><strong>@lang('words.booking_label_name_colon')</strong> {{ $agreement->booking->tenant->full_name }}</p>
                <p><strong>@lang('words.booking_label_email_colon')</strong> {{ $agreement->booking->tenant->email }}</p>
                <p><strong>@lang('words.booking_label_phone_no_colon')</strong> {{ $agreement->booking->tenant->first_phoneNumber }}</p>
            </div>
            <div class="info-box">
                <h3>@lang('words.landlord_info_title')</h3>
                <p><strong>@lang('words.booking_label_name_colon')</strong> {{ $agreement->booking->house->landlord->full_name }}</p>
                <p><strong>@lang('words.booking_label_email_colon')</strong> {{ $agreement->booking->house->landlord->email }}</p>
                <p><strong>@lang('words.booking_label_phone_no_colon')</strong> {{ $agreement->booking->house->landlord->first_phoneNumber }}</p>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">@lang('words.property_info_title')</h2>
            <div class="info-box">
                <p><strong>@lang('words.property_name_label'):</strong> {{ $agreement->booking->house->title }}</p>
                <p><strong>@lang('words.property_address_label'):</strong> {{ $agreement->booking->house->full_address }}</p>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">@lang('words.additional_notes_label')</h2>
            <div class="info-box">
                <p>{{ $agreement->notes }}</p>
            </div>
        </div>

        <div class="footer">
            <p>@lang('words.footer_copyright', ['year' => date('Y')])</p>
        </div>
    </div>
</body>
</html>
