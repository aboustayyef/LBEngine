@extends('layout')

@section('content')

@foreach($posts as $post)
<div class="post">

	<div class="post__image">
		@if($post->media()->count() > 0)
			<img src="/img/{{$post->media->hash}}.jpg" alt="">
		@endif
	</div>

	<div class="post__content">

		<h2 class="post__title">
			<a href="{{$post->url}}">{{$post->title}}</a>
		</h2>

		<p class="post__excerpt">
			{{str_limit($post->excerpt, 180)}}
		</p>

		<div class="post__metadata">
			<span class="source">{{$post->source->name}}</span> <span class="info">{{$post->publishing_date->diffForHumans()}}, Shares: {{$post->shares}} {{-- Score: $post->score --}}</span>
		</div>

	</div>
	
</div>
@endforeach

@stop
