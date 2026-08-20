<div class="text-nowrap">
    <a href="{{ route('clients.show', $client->id) }}"
        class="btn btn-info btn-sm"
        title="Detail">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('clients.edit', $client->id) }}"
        class="btn btn-warning btn-sm"
        title="Edit">
        <i class="fas fa-pen"></i>
    </a>
    <button type="button"
        class="btn btn-danger btn-sm btn-delete-client"
        data-id="{{ $client->id }}"
        data-name="{{ $client->client_name }}"
        title="Hapus">
        <i class="fas fa-trash"></i>
    </button>
</div>