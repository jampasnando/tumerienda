@php
    use Carbon\Carbon;

    $oferta = \App\Models\Oferta::find($get('oferta_id'));
    $menus = $oferta?->menus ?? collect();

    $dias = [];

    if ($oferta) {
        $inicio = Carbon::parse($oferta->fecha_inicio);
        $fin = Carbon::parse($oferta->fecha_fin);

        while ($inicio->lte($fin)) {

            if (!$inicio->isSunday()) {
                $dias[] = [
                    'fecha' => $inicio->format('Y-m-d'),
                    'label' => $inicio->translatedFormat('D d M'),
                ];
            }

            $inicio->addDay();
        }
    }
@endphp

<div style="width: 100%;">

    {{-- DEBUG OPCIONAL --}}
    <div style="margin-bottom:10px;">
        Oferta: {{ $get('oferta_id') }} --------------------------------------------------------------------------------------------------------
    </div>

    @if(!$oferta)
        <div style="color: gray;">
            Selecciona una oferta para ver el calendario.
        </div>
    @else

        <div class="cal-grid">

            @foreach ($dias as $dia)

                @php
                    $statePath = $getStatePath().".{$dia['fecha']}";
                    $selected = data_get($getState(), $dia['fecha']);
                @endphp

                <div class="cal-day">

                    {{-- Día --}}
                    <div class="cal-title">
                        {{ $dia['label'] }}
                    </div>

                    {{-- MENÚS CON ALPINE --}}
                    <div
                        x-data="{ selected: @js($selected) }"
                        class="space-y-2"
                    >

                        @foreach ($menus as $menu)

                            <div
                                @click="
                                    selected = {{ $menu->id }};
                                    $wire.set('{{ $statePath }}', {{ $menu->id }});
                                "
                                class="cal-card"
                                :class="selected == {{ $menu->id }} ? 'selected' : ''"
                                style="cursor:pointer;"
                            >

                                @if($menu->foto)
                                    <img
                                        src="{{ asset('storage/'.$menu->foto) }}"
                                        style="width:100%; height:80px; object-fit:cover; border-radius:6px; margin-bottom:4px;"
                                    >
                                @endif

                                <div style="font-size:12px; font-weight:600;">
                                    {{ $menu->nombre }}
                                </div>

                                <div style="font-size:12px; color:#16a34a; font-weight:bold;">
                                    Bs {{ number_format($menu->precio, 2) }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</div>

{{-- 🔥 CSS RESPONSIVE --}}
<style>
.cal-grid {
    display: grid;
    gap: 12px;

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
    border-radius: 10px;
    padding: 8px;
    background: white;
}

.cal-title {
    text-align: center;
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 6px;
}

.cal-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 6px;
    margin-bottom: 6px;
    text-align: center;
    transition: 0.2s;
}

.cal-card:hover {
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.cal-card.selected {
    border: 2px solid #3b82f6;
    background: #eff6ff;
}
</style>
