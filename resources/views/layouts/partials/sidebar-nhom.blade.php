@php
    $items = $items ?? [];
    $nhomTitle = $nhomTitle ?? '';
    $icon = $icon ?? 'clock';
    $dangChon = function ($item) use ($currentRoute) {
        if (!empty($item['path']) && str_contains(request()->path(), $item['path'])) {
            return true;
        }
        if (!empty($item['prefix']) && str_starts_with((string) $currentRoute, $item['prefix'])) {
            return true;
        }
        return in_array($currentRoute, $item['active'] ?? [], true);
    };
    $moNhom = false;
    foreach ($items as $item) {
        if ($dangChon($item)) {
            $moNhom = true;
            break;
        }
    }
    $linkClass = 'flex items-center px-3 py-2.5 rounded-lg transition-colors';
    $activeClass = 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
    $idleClass = 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700';
@endphp

@if (count($items) === 1)
    @php $item = $items[0]; $href = $item['url'] ?? route($item['route']); @endphp
    <li>
        <a href="{{ $href }}" class="{{ $linkClass }} {{ $dangChon($item) ? $activeClass : $idleClass }}">
            @include('layouts.partials.sidebar-icon', ['icon' => $icon])
            <span class="font-medium menu-text">{{ $item['title'] }}</span>
            @if (!empty($item['badge']))
                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $item['badge'] }}</span>
            @endif
        </a>
    </li>
@elseif (count($items) > 1)
    <li>
        <details class="menu-details" {{ $moNhom ? 'open' : '' }}>
            <summary class="flex items-center w-full px-3 py-2.5 rounded-lg transition-colors cursor-pointer {{ $moNhom ? $activeClass : $idleClass }}">
                @include('layouts.partials.sidebar-icon', ['icon' => $icon])
                <span class="flex-1 text-left font-medium menu-text">{{ $nhomTitle }}</span>
                @php $badgeNhom = collect($items)->sum(fn ($item) => (int) ($item['badge'] ?? 0)); @endphp
                @if ($badgeNhom > 0)
                    <span class="mr-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $badgeNhom }}</span>
                @endif
                <svg class="w-4 h-4 transition-transform duration-200 arrow-icon flex-shrink-0 {{ $moNhom ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </summary>
            <ul class="pl-10 mt-1 space-y-1">
                @foreach ($items as $item)
                    @php $href = $item['url'] ?? route($item['route']); @endphp
                    <li>
                        <a href="{{ $href }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ $dangChon($item) ? $activeClass : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <span class="menu-text">{{ $item['title'] }}</span>
                            @if (!empty($item['badge']))
                                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </details>
    </li>
@endif
