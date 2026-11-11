<form method="POST" action="{{ route('weather.refresh') }}" class="row">
    @csrf
    <span>Last update: {{ $updated ? \Carbon\Carbon::parse($updated)->format('M d, Y g:i A') : 'none yet' }}</span>
    <button>Refresh weather</button>
</form>

@php
    $order = ['High' => 0, 'Moderate' => 1, 'Low' => 2, '-' => 3];
    $sorted = $municipalities->sortBy(fn ($m) => $order[$m->risk]);
@endphp

<table>
    <tr>
        <th>Municipality</th>
        <th>Temp</th>
        <th>Rain today</th>
        <th>Humidity</th>
        <th>Risk</th>
    </tr>
    @foreach($sorted as $m)
        <tr>
            <td class="town">{{ $m->name }}</td>
            <td>{{ $m->temp_c ?? '-' }} °C</td>
            <td>
                {{ $m->rainfall_mm ?? '-' }} mm
                <div class="bar"><span style="width: {{ min(($m->rainfall_mm ?? 0) / 60 * 100, 100) }}%"></span></div>
            </td>
            <td>{{ $m->humidity ?? '-' }}%</td>
            <td><span class="risk {{ $m->risk }}">{{ $m->risk }}</span></td>
        </tr>
    @endforeach
</table>

<small>Weather source: Open-Meteo (automatic, updates every hour).</small>
