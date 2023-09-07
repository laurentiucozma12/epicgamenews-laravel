@extends('layout')

@section('title', 'Categories | Epic Game News')

@section('content')

<div class="colorlib-blog">
	<div class="container">
		<div class="row">
			<div class="col-md-12 categories-col">

                <div class="row">

                    @forelse ($categories as $category)
                        <div class='col-md-3'>
                            <div class="block-21 d-flex animate-box post">
                                <div class="text category-container">
                                    <h3 class="heading"><a href="{{ route('categories.show', $category) }}"> {{ $category->name }} </a></h3>
                                    <div class="meta">
                                        <div><a class='date' href="#"><span class="icon-calendar"></span> {{ $category->created_at->diffForHumans() }} </a></div>
                                        <br>
                                        <div><a href="#"><span class="icon-user2"></span> {{ $category->user->name }} </a></div>
                                        <br>
                                        <div class="posts-count">
                                            <a href="{{ route('categories.show', $category) }}">
                                                <span class="icon-tag"></span> {{ $category->posts_count . (($category->posts_count === 1) ? ' Article' : ' Articles') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="lead">There are no categories to show.</p>
                    @endforelse
                    
                </div>

                {{ $categories->links() }}

			</div>
		</div>
	</div>
</div>

@endsection