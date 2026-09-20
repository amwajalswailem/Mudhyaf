@extends('admin.layouts.app')

@section('role', 'Admin')

@section('content')
    <div class="space-y-8">
        <div class="admin-recommendation-hero">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.28em] text-[--primary-gold]">Recommendation Monitor</p>
                <h1 class="text-3xl font-extrabold text-white mt-3">Usage and ranking activity</h1>
                <p class="text-white mt-3 max-w-3xl">
                    Monitor how often recommendations are generated, which places appear most often, and how users interact with the system.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="admin-kpi-card">
                    <span>Total logs</span>
                    <strong>{{ $metrics['total_logs'] }}</strong>
                </div>
                <div class="admin-kpi-card">
                    <span>Users</span>
                    <strong>{{ $metrics['unique_users'] }}</strong>
                </div>
                <div class="admin-kpi-card">
                    <span>Today</span>
                    <strong>{{ $metrics['today_logs'] }}</strong>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-7 bg-white rounded-[2rem] border border-gray-100 shadow p-6">
                <div class="flex items-center justify-between gap-4 mb-5">
                    <h2 class="text-xl font-extrabold text-gray-900">Top recommended places</h2>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">By frequency</span>
                </div>

                <div class="space-y-4">
                    @foreach($metrics['top_entities'] as $row)
                        <div class="recommendation-admin-row">
                            <div>
                                <p class="font-bold text-gray-900">{{ $row->entity?->name ?? class_basename($row->entity_type) . ' #' . $row->entity_id }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ class_basename($row->entity_type) }} · Best score {{ number_format((float) $row->best_score, 1) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-extrabold text-[--deep-green]">{{ $row->total }}</p>
                                <p class="text-xs text-gray-500">appearances</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-5 bg-white rounded-[2rem] border border-gray-100 shadow p-6">
                <div class="flex items-center justify-between gap-4 mb-5">
                    <h2 class="text-xl font-extrabold text-gray-900">Latest activity</h2>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Generated at</span>
                </div>

                <div class="space-y-3">
                    @foreach($metrics['recent_logs']->take(5) as $log)
                        <div class="flex items-center justify-between rounded-2xl border border-gray-100 bg-gray-50 px-4 py-3">
                            <div>
                                <p class="font-semibold text-gray-700">{{ $log->entity?->name ?? class_basename($log->entity_type) . ' #' . $log->entity_id }}</p>
                                <p class="text-xs text-gray-500">{{ optional($log->generated_at)->format('M d, H:i') }}</p>
                            </div>
                            <strong class="text-gray-900">{{ number_format((float) $log->score, 1) }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow p-6">
            <div class="flex items-center justify-between gap-4 mb-5">
                <h2 class="text-xl font-extrabold text-gray-900">Recent recommendation events</h2>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Latest 12</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-gray-500 uppercase tracking-widest text-xs">
                        <tr>
                            <th class="py-3 pr-4">User</th>
                            <th class="py-3 pr-4">Item</th>
                            <th class="py-3 pr-4">Score</th>
                            <th class="py-3 pr-4">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($metrics['recent_logs'] as $log)
                            <tr>
                                <td class="py-4 pr-4 font-medium text-gray-900">{{ $log->user?->full_name ?? 'Unknown' }}</td>
                                <td class="py-4 pr-4">
                                    {{ $log->entity?->name ?? class_basename($log->entity_type) . ' #' . $log->entity_id }}
                                </td>
                                <td class="py-4 pr-4 font-semibold text-[--deep-green]">{{ number_format((float) $log->score, 1) }}</td>
                                <td class="py-4 pr-4 text-gray-500">{{ optional($log->generated_at)->format('M d, Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
