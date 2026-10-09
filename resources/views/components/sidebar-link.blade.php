@props(['href', 'icon', 'label', 'active' => false])
<a href="{{ $href }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
          {{ $active ? 'bg-gradient-to-r from-purple-50 to-blue-50 text-[#6C3DE6] font-semibold shadow-xs' : 'text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6]' }}">
    <span class="text-base">{{ $icon }}</span>
    <span>{{ $label }}</span>
</a>
