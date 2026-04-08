// @php
//     $loginTime = $loginTime ?? null;
//     $pendingVisits = $pendingVisits ?? collect([]);
// @endphp

// <x-filament-widgets::widget class="fi-wo-guard-dashboard">
//     <x-filament::section>
//         <div class="space-y-6">
//             <!-- Header -->
//             <div class="space-y-3">
//                 <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">
//                     Unidad de control de visitas
//                 </p>
//                 <h2 class="text-2xl font-bold text-gray-950 dark:text-white">
//                     Panel operativo — Cárcel El Redentor
//                 </h2>
//                 <p class="text-sm text-gray-600 dark:text-gray-400">
//                     Registro de prisioneros, visitantes y citas. Las visitas presenciales se autorizan únicamente
//                     <span class="font-medium">domingos, 14:00 a 17:00</span>, sin solapar horarios del mismo interno.
//                 </p>
//             </div>

//             <!-- Session Info -->
//             <div class="grid gap-4 sm:grid-cols-2">
//                 <div class="space-y-1 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
//                     <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">
//                         Sesión del guardia
//                     </p>
//                     <p class="text-lg font-semibold text-gray-950 dark:text-white">
//                         {{ auth()->user()->name }}
//                     </p>
//                     @if($loginTime)
//                         <p class="text-xs text-gray-600 dark:text-gray-400">
//                             Inició sesión: <span class="font-medium">{{ $loginTime }}</span>
//                         </p>
//                     @endif
//                 </div>

//                 <div class="space-y-1 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
//                     <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">
//                         Ventana legal
//                     </p>
//                     <dl class="space-y-1 text-sm">
//                         <div class="flex justify-between">
//                             <dt class="text-gray-600 dark:text-gray-400">Domingo</dt>
//                             <dd class="font-medium text-gray-950 dark:text-white">14:00 - 17:00</dd>
//                         </div>
//                     </dl>
//                 </div>
//             </div>

//             <!-- Pending Visits -->
//             <div class="space-y-3">
//                 <div class="flex items-center justify-between">
//                     <div>
//                         <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">
//                             Siguientes visitas pendientes
//                         </p>
//                         <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
//                             Las próximas 5 visitas con horario asignado
//                         </p>
//                     </div>
//                     <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-100">
//                         {{ $pendingVisits->count() }} pendientes
//                     </span>
//                 </div>

//                 @if($pendingVisits->isEmpty())
//                     <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 text-center text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
//                         No hay visitas pendientes registradas para los próximos horarios.
//                     </div>
//                 @else
//                     <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
//                         <table class="w-full text-sm">
//                             <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
//                                 <tr>
//                                     <th class="px-4 py-3 text-left font-semibold text-gray-950 dark:text-white">Fecha</th>
//                                     <th class="px-4 py-3 text-left font-semibold text-gray-950 dark:text-white">Hora</th>
//                                     <th class="px-4 py-3 text-left font-semibold text-gray-950 dark:text-white">Prisionero</th>
//                                     <th class="px-4 py-3 text-left font-semibold text-gray-950 dark:text-white">Visitante</th>
//                                 </tr>
//                             </thead>
//                             <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
//                                 @foreach($pendingVisits as $visit)
//                                     <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
//                                         <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
//                                             {{ $visit->start_time->translatedFormat('d/m/Y') }}
//                                         </td>
//                                         <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
//                                             {{ $visit->start_time->format('H:i') }} - {{ $visit->end_time->format('H:i') }}
//                                         </td>
//                                         <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
//                                             {{ $visit->prisoner->name ?? 'Sin prisionero' }}
//                                         </td>
//                                         <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
//                                             {{ $visit->visitor->name ?? 'Sin visitante' }}
//                                         </td>
//                                     </tr>
//                                 @endforeach
//                             </tbody>
//                         </table>
//                     </div>
//                 @endif
//             </div>
//         </div>
//     </x-filament::section>
// </x-filament-widgets::widget>
