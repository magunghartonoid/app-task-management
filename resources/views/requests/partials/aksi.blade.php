<div class="text-nowrap">
    <a href="{{ route('requests.edit', $request->id) }}"
        class="btn btn-warning btn-sm"
        title="Edit">
        <i class="fas fa-pen"></i>
    </a>
    <button type="button"
        class="btn btn-danger btn-sm btn-delete-request"
        data-id="{{ $request->id }}"
        data-name="{{ Str::limit($request->request, 30) }}"
        title="Hapus">
        <i class="fas fa-trash"></i>
    </button>
</div>