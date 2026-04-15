<x-filament::page>

@php
    $dias = $this->getDias();
    $suscripciones = $this->getSuscripciones();
    $beneficiario = $this->getBeneficiario();
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

.cal-day {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 6px;
    background: white;
    min-height: 90px;
}

.cal-date {
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 4px;
}

.cal-item {
    font-size: 11px;
    background: #dcfce7;
    padding: 4px;
    border-radius: 4px;
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
            <h2 class="text-xl font-bold">
                {{ $beneficiario?->nombre ?? 'Seleccione beneficiario' }}
            </h2>

            <div class="text-sm text-gray-500">
                {{ \Carbon\Carbon::create($this->anio, $this->mes)->translatedFormat('F Y') }}
            </div>
        </div>

        <div class="flex gap-2">
            <button wire:click="prevMes">←</button>
            <button wire:click="nextMes">→</button>
        </div>

    </div>

    {{-- Selector beneficiario --}}
    <div>
        <select wire:model.live="beneficiario_id" class="border rounded p-2 w-full">
            <option value="">Seleccionar beneficiario</option>

            @foreach (\App\Models\Beneficiario::all() as $b)
                <option value="{{ $b->id }}">
                    {{ $b->nombre }}
                </option>
            @endforeach

        </select>
    </div>

    @if($beneficiario_id)

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
                $s = $suscripciones[$fecha] ?? null;
            @endphp

            <div class="cal-day">

                <div class="cal-date">
                    {{ $dia->format('d') }}
                </div>

                @if($s)
                    <div class="cal-item">
                        <div class="font-semibold">
                            {{ $s->menu->nombre }}
                        </div>
                    </div>
                @else
                    <div class="cal-empty">
                        Sin menú
                    </div>
                @endif

            </div>

        @endforeach

    </div>
    @endif

</div>

</x-filament::page>
