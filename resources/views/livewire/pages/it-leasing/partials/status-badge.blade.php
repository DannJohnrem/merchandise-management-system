<span class="
    px-2 py-1 text-xs font-semibold rounded
    @if($status === 'Available') bg-green-100 text-green-700
    @elseif($status === 'In-use') bg-blue-100 text-blue-700
    @elseif($status === 'For Repair') bg-yellow-100 text-yellow-700
    @else bg-gray-200 text-gray-700
    @endif
">
    {{ $status }}
</span>
