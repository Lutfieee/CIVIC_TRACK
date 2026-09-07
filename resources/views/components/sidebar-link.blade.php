@props(['href', 'icon', 'label', 'active' => false])
<a href="{{ $href }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
          {{ $active ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
    <span class="text-base">{{ $icon }}</span>
    <span>{{ $label }}</span>
</a>
