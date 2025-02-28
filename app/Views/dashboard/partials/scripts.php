<!-- Scripts -->

<script src="<?=PL_BASE_DIST . '/js/all.min.js'?>"></script>
<script src="<?=PL_BASE_DIST . '/js/funcoes.js'?>"></script>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?= PL_BASE_DIST ?>/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?=PL_BASE_DIST . '/js/bootstrap.bundle.min.js'?>"></script>
<!-- AdminLTE -->
<script src="<?= PL_BASE_DIST ?>/js/adminlte.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>


<!-- OPTIONAL SCRIPTS -->
<script src="<?= PL_BASE_DIST ?>/js/Chart.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= PL_BASE_DIST ?>/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= PL_BASE_DIST ?>/js/dashboard3.js"></script>



<script>
  $('#summernote').summernote({
    placeholder: '',
    tabsize: 2,
    height: 120,
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'underline', 'clear']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['insert', ['link', 'picture', 'video']],
      ['view', ['fullscreen', 'codeview', 'help']]
    ]
  });
</script>
