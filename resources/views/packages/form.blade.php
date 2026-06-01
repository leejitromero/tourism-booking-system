<form method="POST" action="{{ $action }}" class="card card-body">
@csrf @if($method !== 'POST') @method($method) @endif
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Title</label><input class="form-control" name="title" value="{{ old('title',$package->title ?? '') }}" required></div>
    <div class="col-md-3"><label class="form-label">Category</label><select name="category" class="form-select" required>@foreach(['Hotel','Resort','Beach Resort','Glamping','Cottage','Accommodation','Tour'] as $category)<option value="{{ $category }}" @selected(old('category',$package->category ?? 'Hotel')===$category)>{{ $category }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Stars</label><input class="form-control" type="number" min="0" max="5" name="stars" value="{{ old('stars',$package->stars ?? 0) }}"></div>
    <div class="col-md-4"><label class="form-label">Location</label><input class="form-control" name="location" value="{{ old('location',$package->location ?? '') }}" required></div>
    <div class="col-md-4"><label class="form-label">Distance</label><input class="form-control" name="distance" value="{{ old('distance',$package->distance ?? '') }}" placeholder="200 m from centre"></div>
    <div class="col-md-4"><label class="form-label">Beach Info</label><input class="form-control" name="beach_info" value="{{ old('beach_info',$package->beach_info ?? '') }}" placeholder="Beachfront / 50 m from beach"></div>
    <div class="col-md-4"><label class="form-label">Duration</label><input class="form-control" name="duration" value="{{ old('duration',$package->duration ?? '') }}" placeholder="1 Night"></div>
    <div class="col-md-4"><label class="form-label">Price per night</label><input class="form-control" type="number" step="0.01" name="price" value="{{ old('price',$package->price ?? '') }}" required></div>
    <div class="col-md-4"><label class="form-label">Slots</label><input class="form-control" type="number" min="1" name="slots" value="{{ old('slots',$package->slots ?? '') }}" required></div>
    <div class="col-md-6"><label class="form-label">Review Score</label><input class="form-control" type="number" step="0.1" min="0" max="10" name="review_score" value="{{ old('review_score',$package->review_score ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label">Review Count</label><input class="form-control" type="number" min="0" name="review_count" value="{{ old('review_count',$package->review_count ?? 0) }}"></div>
    <div class="col-12"><label class="form-label">Image path or URL</label><input class="form-control" name="image_url" value="{{ old('image_url',$package->image_url ?? '') }}" placeholder="images/places/sample.jpg or https://..."></div>
    <div class="col-12"><label class="form-label">Amenities</label><input class="form-control" name="amenities" value="{{ old('amenities',$package->amenities ?? '') }}" placeholder="Beachfront, Swimming pool, Free parking"></div>
    <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="5" required>{{ old('description',$package->description ?? '') }}</textarea></div>
</div>
<div class="d-flex gap-2 mt-4"><button class="btn btn-main">Save Accommodation</button><a href="{{ route('admin.packages.manage') }}" class="btn btn-outline-secondary">Cancel</a></div>
</form>
