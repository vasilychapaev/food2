@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Детализация за {{ $summary->date }}</h1>
    <div class="mb-3">
        <strong>Калории:</strong> {{ $summary->total_calories }}<br>
        <strong>Белки:</strong> {{ $summary->total_protein }}<br>
        <strong>Жиры:</strong> {{ $summary->total_fat }}<br>
        <strong>Углеводы:</strong> {{ $summary->total_carbs }}<br>
    </div>
    <h3>Приёмы пищи</h3>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Raw</th>
                <th>Блюда/ингредиенты</th>
                <th>Калории</th>
                <th>Белки</th>
                <th>Жиры</th>
                <th>Углеводы</th>
            </tr>
        </thead>
        <tbody>
        @foreach($summary->meals as $meal)
            <tr>
                <td>{{ $meal['meal_number'] }}</td>
                <td>{{ $meal['raw_entry'] }}</td>
                <td>
                    @foreach($meal['parsed_items'] as $item)
                        <div>{{ $item['name'] }} ({{ $item['weight'] }} г): {{ $item['calories'] }} ккал, Б: {{ $item['protein'] }}, Ж: {{ $item['fat'] }}, У: {{ $item['carbs'] }}</div>
                    @endforeach
                </td>
                <td>{{ $meal['calories'] }}</td>
                <td>{{ $meal['protein'] }}</td>
                <td>{{ $meal['fat'] }}</td>
                <td>{{ $meal['carbs'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a href="{{ url('/tracker') }}" class="btn btn-secondary">Назад к списку</a>
</div>
@endsection 