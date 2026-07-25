<x-filament::page>

@php
    $dias = $this->getDias();
    $resumen = $this->getResumen();
@endphp
<style>
.cal-grid {
    display: grid;
    gap: 8px;

    /* 📱 móvil */
    grid-template-columns: 1fr;
}

/* 📱 tablet */
@media (min-width: 640px) {
    .cal-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* 💻 desktop */
@media (min-width: 1024px) {
    .cal-grid {
        grid-template-columns: repeat(5, 1fr);
    }
}

.cal-day{
    border:1px solid #e5e7eb;
    border-radius:8px;
    padding:8px;
    background:white;
    min-height:170px;
}

.cal-date {
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 4px;
}

.cal-item{
    font-size:11px;
    background:#f3f4f6;
    border-left:4px solid #16a34a;
    border-radius:4px;
    padding:4px 6px;
    margin-bottom:4px;
}

.cal-empty {
    font-size: 10px;
    color: #9ca3af;
}
.cal-header {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    text-align: center;
    font-weight: bold;
    font-size: 12px;
}
</style>
<div class="space-y-4">

    {{-- Header --}}
    <div class="flex justify-between items-center">

        <div>

            <div class="text-sm text-gray-500">
                {{ \Carbon\Carbon::create($this->anio, $this->mes)->translatedFormat('F Y') }}
            </div>
        </div>

        <div class="flex gap-2">
            <button wire:click="prevMes">←</button>
            <button wire:click="nextMes">→</button>
        </div>

    </div>

        {{-- Días semana --}}
        <div class="cal-header">
            <div>Lun</div>
            <div>Mar</div>
            <div>Mié</div>
            <div>Jue</div>
            <div>Vie</div>
        </div>

        {{-- Calendario --}}
    <div class="cal-grid">

    @foreach ($dias as $dia)

    @if(!$dia)
        <div class="cal-day bg-transparent border-none"></div>
        @continue
    @endif

    @php
        $fecha = $dia->format('Y-m-d');
        $menus = $resumen[$fecha] ?? collect();
    @endphp

    <div class="cal-day">

        <div class="cal-date flex justify-between">

            <span>
                {{ $dia->format('d') }}
            </span>

            @if($menus->count())

                <span class="text-xs text-gray-500">

                    {{ $menus->sum('cantidad') }}

                </span>

            @endif

        </div>

        @forelse($menus as $item)

                    <div class="cal-item mb-1">

                        <div class="flex justify-between">

                            <span>

                                {{ $item->menu_nombre }}

                            </span>

                            <span class="font-bold">

                                {{ $item->cantidad }}

                            </span>

                        </div>

                    </div>

                @empty

                    <div class="cal-empty">

                        Sin pedidos

                    </div>

                @endforelse

            </div>

        @endforeach

    </div>

</div>

</x-filament::page>
