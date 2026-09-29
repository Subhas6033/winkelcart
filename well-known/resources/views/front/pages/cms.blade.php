@extends('front.layouts.app')

@section('content')
<div class="container py-5" style="max-width: 1100px;">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h1 class="mb-3" style="font-size: 2rem;">{{ $page->title }}</h1>

                    @if(!empty($page->meta_description))
                        <p class="text-muted mb-4">{{ $page->meta_description }}</p>
                    @endif

                    <div class="cms-content" style="line-height: 1.7;">
                        {!! $page->content ?: '<p class="text-muted">Page content is not available yet.</p>' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
