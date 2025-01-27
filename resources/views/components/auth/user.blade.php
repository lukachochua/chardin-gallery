<form action="{{ route('logout') }}" method="POST" class="inline">
    @csrf
    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-gray-600">
        Logout
    </button>
</form>
