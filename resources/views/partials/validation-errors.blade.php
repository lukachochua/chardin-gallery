@if ($errors->any())
    <div class="alert alert-danger mb-6">
        <ul class="list-disc list-inside text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger mb-6">
        {{ session('error') }}
    </div>
@endif