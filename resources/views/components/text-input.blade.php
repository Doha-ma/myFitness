@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500']) }}
>

