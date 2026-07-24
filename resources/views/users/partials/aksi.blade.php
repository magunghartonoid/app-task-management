<div class="text-nowrap">
    <button type="button"
        class="btn btn-warning btn-sm btn-edit-user"
        data-id="{{ $user->id }}"
        title="Edit">
        <i class="fas fa-pen"></i>
    </button>
    <button type="button"
        class="btn btn-danger btn-sm btn-delete-user"
        data-id="{{ $user->id }}"
        data-name="{{ $user->name }}"
        title="Hapus">
        <i class="fas fa-trash"></i>
    </button>
</div>
