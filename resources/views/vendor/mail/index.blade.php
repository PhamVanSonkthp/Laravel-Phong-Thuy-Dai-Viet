@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
{{--    This is your logo--}}
{{--    ![Some option text][logo]--}}
{{--    [logo]: {{asset('img/my-logo.png')}} "Logo"--}}


    {!! $body !!}

    {{-- Subcopy --}}
    @isset($subcopy)
    @slot('subcopy')
    @component('mail::subcopy')
    {{ $subcopy }}
    @endcomponent
    @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
    @component('mail::footer')
    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
@endcomponent
@endslot
@endcomponent
