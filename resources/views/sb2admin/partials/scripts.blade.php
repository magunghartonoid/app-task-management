{{-- Bootstrap 5 core JS bundle (termasuk Popper) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

{{-- SB Admin core JS (sidebar toggle, dsb) --}}
<script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin@7.0.7/dist/js/scripts.js"></script>

{{-- Chart.js - AKTIFKAN hanya kalau ada halaman yang pakai chart --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script> --}}

{{-- Slot tambahan untuk script khusus per halaman --}}
@stack('scripts')
