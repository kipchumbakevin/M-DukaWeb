@include('layouts.app')
@section('content')
    <form method="post" action="">
        @csrf
        <div class="form-group">
            <label>Subject</label>
            <input class="form-control" name="subject" placeholder="subject">
        </div>
        <div class="form-group">
            <label class="">Description</label>
            <textarea class="form-control" placeholder="email description" rows="5" name="description"></textarea>
            <input type="submit"  class="btn btn-info" value="Send">
        </div>
    </form>
@endsection
