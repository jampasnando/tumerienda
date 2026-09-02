<x-filament::page>

@php
    $dias = $this->getDias();
    $resumen = $this->getResumen();
@endphp
<style>
.\*, .\*::before, .\*::after { box-sizing: border-box; }
.cal-grid{
    display:grid;
    gap:8px;
    grid-template-columns:repeat(7,1fr);
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
    display:grid;
    grid-template-columns:repeat(7,1fr);
    text-align:center;
    font-weight:bold;
    font-size:12px;
}
.cal-header > div {
    display:flex;
    align-items:center;
    justify-content:center;
    background-color: whitesmoke;
}
.cal-header,
.cal-grid{
    width:100%;
    min-width:700px;
    /* grid-auto-rows: minmax(140px, auto); */
}
.cal-header{ min-width:700px; width:100%; }
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

    <div class="overflow-x-auto">
        {{-- Días semana --}}
        <div class="cal-header">
            <div>Lun</div>
            <div>Mar</div>
            <div>Mié</div>
            <div>Jue</div>
            <div>Vie</div>
            <div>Sáb</div>
            <div>Dom</div>
        </div>
        <div
            wire:key="calendario-{{ $this->anio }}-{{ $this->mes }}"
            class="cal-grid"
        >
            {{-- Calendario --}}
            @foreach ($dias as $index => $dia)

                @if(!$dia)
                    <div
                        wire:key="vacio-{{ $this->anio }}-{{ $this->mes }}-{{ $index }}"
                        class="cal-day bg-transparent border-none"
                    ></div>
                    @continue
                @endif

                @php
                    $fecha = $dia->format('Y-m-d');
                    $cobros = $resumen[$fecha] ?? collect();
                @endphp

                <div
                    wire:key="dia-{{ $fecha }}"
                    class="cal-day block hover:bg-gray-50 transition"
                    style="background:lemonchiffon"
                >

                    <div class="cal-date flex justify-between">
                        <span>
                            {{ $dia->format('d') }}
                        </span>
                    </div>

                    @forelse($cobros as $item)

                        <div class="cal-item mb-1">
                            <div class="flex justify-between gap-2">
                                <span class="truncate">
                                    {{ $item->beneficiario_nombre ?: ($item->nombreCliente ?: 'Sin beneficiario') }}
                                </span>
                                {{-- <span class="font-bold whitespace-nowrap">
                                    {{ $item->monto }}
                                </span> --}}
                            </div>
                            <div class="text-[10px] text-gray-500">
                                {{ $item->plan_nombre ?: 'Sin plan' }}
                            </div>
                            {{-- <div class="text-[10px] text-gray-500">
                                {{ $item->alias }}
                            </div> --}}
                            <div class="text-[8px] text-gray-300">
                                {{ $item->nombreCliente ? 'Cliente: '.$item->nombreCliente : '' }}
                            </div>
                        </div>

                    @empty

                        <div class="cal-empty">
                            Sin cobros
                        </div>

                    @endforelse

                </div>

            @endforeach
        </div>
    </div>

</div>

</x-filament::page>
