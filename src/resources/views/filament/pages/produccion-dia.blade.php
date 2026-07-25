<x-filament-panels::page>
    @foreach($this->getProduccion() as $colegio=>$beneficiarios)

    <x-filament::section>

        <x-slot name="heading">

            {{ $colegio }}

        </x-slot>

        @foreach($beneficiarios as $items)

            @php
                $benef=$items->first()->beneficiario;
            @endphp

            <div class="border-b py-2">

                <div class="font-bold">

                    {{ $benef->nombre }}

                    {{ $benef->apellidos }}

                </div>

                <ul>

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
