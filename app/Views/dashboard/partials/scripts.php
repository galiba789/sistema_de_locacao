<!-- Scripts -->

<script src="<?=PL_BASE_DIST . '/js/all.min.js'?>"></script>
<script src="<?=PL_BASE_DIST . '/js/funcoes.js?v=1.2'?>"></script>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?= PL_BASE_DIST ?>/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?=PL_BASE_DIST . '/js/bootstrap.bundle.min.js'?>"></script>
<!-- AdminLTE -->
<script src="<?= PL_BASE_DIST ?>/js/adminlte.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<link rel="stylesheet" href="<?= PL_BASE_DIST . '/css/bootstrap.min.css'?>">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
<script src="https://unpkg.com/imask"></script>

<!-- DataTables JS -->

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="<?= PL_BASE_DIST ?>/js/Chart.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= PL_BASE_DIST ?>/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= PL_BASE_DIST ?>/js/dashboard3.js"></script>

<script src="<?=PL_BASE_DIST?>/js/jquery.mask.js"></script>
<script src="<?=PL_BASE_DIST?>/js/jquery.mask.min.js"></script>




<script>
  $("#telefone_contato").bind('input propertychange', function() {
    var texto = $(this).val();
    
    texto = texto.replace(/[^\d]/g, '');
    
    if (texto.length > 0) {
      texto = "(" + texto;
    
      if (texto.length > 3) {
        texto = [texto.slice(0, 3), ")", texto.slice(3)].join('');
      }
      if (texto.length >= 9 && texto.length <= 12) {
        texto = [texto.slice(0, 8), "-", texto.slice(8)].join('');
      } else if (texto.length > 12)
      texto = [texto.slice(0, 9), "-", texto.slice(9)].join('');
      
      if (texto.length > 14)
      texto = texto.substr(0, 14);
  }
  $(this).val(texto);
});
$(document).ready(function(){
    $('#cpf').mask('000.000.000-00');
    $('#cep').mask('00000-000');
    $('#data').mask('00/00/0000');
    $('#desconto').mask('000.000.000.000.000,00', {reverse: true});
    $('#valor_total').mask('000.000.000.000.000,00', {reverse: true});
    $('#valor_total').mask('000.000.000.000.000,00', {reverse: true});
    $('#subtotal').mask('000.000.000.000.000,00', {reverse: true});
    $('#cnpj').mask('00.000.000/0000-00');
    $('#telefone_contato_cnpj').mask('(00)00000-0000');
    $('#telefone_contato_fisica').mask('(00)00000-0000');
    $('#telefone_comercial').mask('(00)00000-0000');
  });

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

  $(document).ready(function() {
        $('.ultimas_loc').DataTable({
            "pageLength": 5, // Número de registros por página
            "order": [[0, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/Portuguese-Brasil.json"
            }
        });
        $('.agendadas').DataTable({
            "pageLength": 5, // Número de registros por página
            "order": [[0, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/Portuguese-Brasil.json"
            }
        });
        $('.finalizadas').DataTable({
            "pageLength": 5, // Número de registros por página
            "order": [[0, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/Portuguese-Brasil.json"
            }
        });
        $('.ultimas_loc_cliente').DataTable({
            "pageLength": 10, // Número de registros por página
            "order": [[0, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/Portuguese-Brasil.json"
            }
        });
    });
</script>
