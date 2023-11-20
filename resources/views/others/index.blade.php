@extends('layout')

@section('title', 'Other | Epic Game News')

@section('content')

<div class="colorlib-blog">
	<div class="container">
		<div class="row">
			<div class="col-md-12 categories-col">

                <div class="row">
                    @forelse ($others as $other)
                        <div class='col-md-3'>
                            <div class="block-21 d-flex animate-box post">

                                <div class="category-container">
                                    <a href="{{ route('others.show', $other) }}">
                                        <div class="image-container">
                                            <img src="{{ asset($other->image ? 'storage/' . $other->image->path : 'storage/placeholders/thumbnail_placeholder.jpg') }}">
                                        </div>
                                        <div class="text-container">
                                            <h3 class="heading">{{ $other->name }}</h3>
                                            <div class="meta">
                                                <div class="posts-count">
                                                    <span class="icon-tag"></span> {{ $other->posts_count . (($other->posts_count === 1) ? ' Article' : ' Articles') }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            </div>
                        </div>
                    @empty
                        <p class="lead">There is no other to show.</p>
                    @endforelse
                    
                </div>

				{{ $others->onEachSide(1)->links('pagination::bootstrap-4') }}
                
                <div class="row">
                    <div class="col-12">  
                        <div class="d-none d-md-block">
                            <x-google-ads.responsive-horizontal-ad/>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-block d-md-none">
                            <x-google-ads.in-article-ad/>
                        </div>
                    </div>
                </div>

			</div>
		</div>
	</div>
</div>

@endsection