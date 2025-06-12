<form action="{{route('checkout')}}" method="POST">
@csrf
<button type="submit">@lang('words.payment_checkout_button')</button>
</form>
