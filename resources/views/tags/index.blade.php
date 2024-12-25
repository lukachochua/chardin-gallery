<x-layouts.admin>
    <div class="container">
        <h1 class="mb-4">Tags</h1>
        
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary mb-3">Add New Tag</a>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tags as $tag)
                        <tr>
                            <td>{{ $tag->name }}</td>
                            <td>
                                <a href="{{ route('admin.tags.edit', $tag->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
