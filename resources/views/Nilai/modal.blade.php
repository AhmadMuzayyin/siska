<div>
    @livewire('pilih-santri-kelas')
</div>
<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('printSantri', function(santriId) {
            if (santriId) {
                window.open('/nilai/print/' + santriId, '_blank');
            }
        });
    });
</script>
