@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5">
    <h1 class="text-3xl font-bold">{{ $spot->title }}</h1>
    <p>{{ $spot->description }}</p>
    <p>場所: {{ $spot->prefecture }} {{ $spot->city }}</p>
    <p>日付: {{ $spot->date_visited }}</p>
    <p>お子様の年齢: {{ $spot->child_age_range }}</p>
    <p>おすすめ度: {{ $spot->rating }} ☆</p>
    @if($spot->photo)
        <img src="{{ Storage::url($spot->photo) }}" alt="{{ $spot->title }}">
    @endif
    @if($spot->
