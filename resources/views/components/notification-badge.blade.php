@props(['count' => 0])

@if($count > 0)
<span class="absolute -top-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold text-white bg-red-500">
    {{ $count > 99 ? '99+' : $count }}
</span>
@endif
