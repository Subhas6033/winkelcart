@extends('layouts.app')

@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <div class="row mt-5">
      <div class="col-xl-12 mt-3">
        @if(Session::has('success'))
        <div>
          <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success') }}</p>
        </div>
        @endif
        <div class="card">
          <div class="card-header border-0">
            <div class="row align-items-center">
              <div class="col">
                <h5 class="info-box-text mb-0"><b>Product Reviews</b></h5>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th>Sl No</th>
                  <th>Product</th>
                  <th>User</th>
                  <th>Rating</th>
                  <th>Comment</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($reviews as $key => $review)
                <tr>
                  <td>{{ $reviews->firstItem() + $key }}</td>
                  <td>{{ $review->product->name ?? '-' }}</td>
                  <td>{{ $review->user->name ?? 'Anonymous' }}</td>
                  <td>{{ $review->rating }}</td>
                  <td>{{ $review->comment }}</td>
                  <td>{{ $review->created_at->format('d M Y') }}</td>
                  <td>
                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this review?')">Delete</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            {{ $reviews->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
