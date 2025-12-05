<?= $this->extend('dashboard/layout') ?>
<?= $this->section('content-wrapper') ?>

<div class="card">
    <div class="card-body text-center">
        <h4>Escaneie o QR Code do WhatsApp</h4>
        <img id="qrCode" src="<?= $qrcode ?>" alt="QR Code WhatsApp">
        <p>Aguardando conexão...</p>
    </div>
</div>

<script>
setInterval(() => {
    fetch("<?= site_url('/whatsapp/check_status/'.$nome) ?>")
        .then(res => res.json())
        .then(data => {
            if(data.status === 'CONNECTED') {
                window.location.href = "<?= site_url('/whatsapp') ?>";
            }
        });
}, 3000);
</script>

<?= $this->endSection() ?>