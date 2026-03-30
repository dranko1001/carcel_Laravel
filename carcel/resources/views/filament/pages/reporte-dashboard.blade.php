<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Filtros --}}
        <div class="bg-gray-800 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Filtrar por rango de fechas</h2>
            <form wire:submit.prevent class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                {{ $this->form }}
                <div class="flex gap-3 mt-2">
                    <button wire:click="exportarExcel" type="button"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                        📊 Exportar Excel
                    </button>
                    <button wire:click="exportarPdf" type="button"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                        📄 Exportar PDF
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabla de resultados --}}
        @php $visitas = $this->getVisitas(); @endphp

        @if($visitas->count() > 0)
            <div class="bg-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">
                    Resultados: {{ $visitas->count() }} visita(s) encontrada(s)
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-300">
                        <thead class="text-xs uppercase text-gray-400 border-b border-gray-600">
                            <tr>
                                <th class="py-3 px-4">Prisionero</th>
                                <th class="py-3 px-4">Visitante</th>
                                <th class="py-3 px-4">Relación</th>
                                <th class="py-3 px-4">Guardia</th>
                                <th class="py-3 px-4">Inicio</th>
                                <th class="py-3 px-4">Fin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($visitas as $visita)
                                <tr class="hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">{{ $visita->prisoner->full_name ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $visita->visitor->full_name ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $visita->relationship }}</td>
                                    <td class="py-3 px-4">{{ $visita->officer->name ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($visita->start_time)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($visita->end_time)->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($this->fecha_inicio && $this->fecha_fin)
            <div class="bg-gray-800 rounded-xl p-6 text-center text-gray-400">
                No se encontraron visitas en ese rango de fechas.
            </div>
        @endif

    </div>
</x-filament-panels::page>