<form action="{{ route('statuses.store') }}" method="POST">
    @include('shared._errors')
    @csrf
    <textarea class="form-control" rows="3" placeholder="微博内容" name="content">
        {{ old('content')}}
    </textarea>
    <div class="text-right">
        <button type="submit" class="btn btn-primary mt-3">发布</button>
    </div>
</form>