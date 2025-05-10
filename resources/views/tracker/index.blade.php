@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Дневные отчёты</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Калории</th>
                <th>Белки</th>
                <th>Жиры</th>
                <th>Углеводы</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($summaries as $summary)
            <tr>
                <td>{{ $summary->date }}</td>
                <td>{{ $summary->total_calories }}</td>
                <td>{{ $summary->total_protein }}</td>
                <td>{{ $summary->total_fat }}</td>
                <td>{{ $summary->total_carbs }}</td>
                <td><a href="{{ url('/tracker/'.$summary->date) }}" class="btn btn-primary btn-sm">Подробнее</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection 