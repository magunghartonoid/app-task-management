<div class="text-nowrap">
    <a href="{{ route('users.edit', $user->id) }}"
        class="btn btn-warning btn-sm"
        title="Edit">
        <i class="fas fa-pen"></i>
    </a>
    <button type="button"
        class="btn btn-danger btn-sm btn-delete-user"
        data-id="{{ $user->id }}"
        data-name="{{ $user->name }}"
        title="Hapus">
        <i class="fas fa-trash"></i>
    </button>
</div>
