@php
    $ofertaId = $get('../../oferta_id');

    $menus = \App\Models\Oferta::find($ofertaId)?->menus ?? collect();
@endphp

<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    @foreach ($menus as $menu)
        <label class="cursor-pointer">
            <input
                type="radio"
                name="{{ $getStatePath() }}"
                value="{{ $menu->id }}"
                wire:model="{{ $getStatePath() }}"
                class="hidden"
            />

            <div class="
                border rounded-xl p-3 shadow-sm
                hover:shadow-md transition
                {{ $getState() == $menu->id ? 'ring-2 ring-primary-500' : '' }}
            ">

                {{-- Imagen --}}
                @if($menu->foto)
                    {{-- <img
                        src="{{ asset('storage/'.$menu->foto) }}"
                        class="w-16 h-16 object-cover rounded-lg mb-2"
                    > --}}
                @endif

                {{-- Nombre --}}
                <div class="font-semibold text-sm">
                    {{ $menu->nombre }}
                </div>

                {{-- Precio --}}
                <div class="text-primary-600 font-bold">
                    Bs {{ number_format($menu->precio, 2) }}
                </div>

                {{-- Descripción opcional --}}
                @if($menu->descripcion)
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $menu->descripcion }}
                    </div>
                @endif

            </div>
        </label>
    @endforeach
</div>
