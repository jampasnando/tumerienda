<x-filament-panels::page>
    <style>
        .nombre{
            font-weight: bold;
            size:1.2em;
        }
        .meriendas{
            margin-left: 2em;
        }
        .paratiquear{
            font-size: 2em;
        }
    </style>
    @foreach($this->getProduccion() as $colegio=>$beneficiarios)

    <x-filament::section>

        <x-slot name="heading">

            {{ $colegio }}

        </x-slot>

        @foreach($beneficiarios as $items)

            @php
                $benef=$items->first()->beneficiario;
                $colegio = $benef->nombrecolegioActivo;
            @endphp

            <div class="border-b py-2">

                <div class="nombre">
                    {{-- <input type="checkbox" class="paratiquear"> --}}
                    {{ $colegio?->codigo }}
                    -
                    {{ $benef->nombre }}

                    {{ $benef->apellidos }}

                </div>

                <ul class="meriendas">

                @foreach($items as $s)

                    <li>

                        {{ $s->menu->nombre }}

                    </li>

                @endforeach

                </ul>

            </div>

        @endforeach

    </x-filament::section>

@endforeach
</x-filament-panels::page>
