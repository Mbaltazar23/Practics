<script>
    const base_url = "<?= base_url(); ?>";
</script>
<!-- Essential javascripts for application to work-->
<script src="<?= media(); ?>/js/jquery-3.3.1.min.js"></script>
<script src="<?= media(); ?>/js/popper.min.js"></script>
<script src="<?= media(); ?>/js/bootstrap.min.js"></script>
<script src="<?= media(); ?>/js/main.js"></script>
<script src="<?= media(); ?>/js/fontawesome.js"></script>
<!-- The javascript plugin to display page loading on top-->
<script src="<?= media(); ?>/js/plugins/pace.min.js"></script>
<!-- Page specific javascripts-->
<script type="text/javascript" src="<?= media(); ?>/js/plugins/swettalert.min.js" ></script>
<script type="text/javascript" src="<?= media(); ?>/js/tinymce/tinymce.min.js"></script>
<!-- Data table plugin-->
<script type="text/javascript" src="<?= media(); ?>/js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/plotly-latest.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/highcharts.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/exporting.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/export-data.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/jspdf.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/plugins/jspdf.plugin.autotable.js" ></script>
<script type="text/javascript" src="<?= media(); ?>/js/datepicker/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/functions_admin.js"></script>
<script type="text/javascript" src="<?= media(); ?>/js/<?= $data['page_functions_js']; ?>"></script>
</body>
</html>