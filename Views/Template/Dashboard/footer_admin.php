<!-- js -->
<script src="<?= media(); ?>/js/core.js" type="text/javascript"></script>
<script src="<?= media(); ?>/js/script.min.js" type="text/javascript"></script>
<script src="<?= media(); ?>/js/process.js" type="text/javascript"></script>
<script src="<?= media(); ?>/js/layout-settings.js" type="text/javascript"></script>
<script src="<?= media(); ?>/js/plugins/apexcharts/apexcharts.min.js" type="text/javascript"></script>
<script src="<?= media(); ?>/js/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?= media(); ?>/js/plugins/datatables/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="<?= media(); ?>/js/plugins/datatables/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="<?= media(); ?>/js/plugins/datatables/js/responsive.bootstrap4.min.js" type="text/javascript"></script>
<script src="<?= media(); ?>/js/plugins/sweetalert2/sweetalert.min.js" type="text/javascript"></script>
<?php if (isset($_SESSION["login"])) { ?>
    <script>
        const id = <?= $_SESSION["idPersona"] ?>
    </script>
    <!--    <script src="<?= media(); ?>/js/actions/functions_perfil.js" type="text/javascript"></script>-->
    <?php if ($_SESSION["cargo-personal"] != ROLADMIN) { ?>
            <!--        <script src="<?= media(); ?>/js/actions/functions_chatUser.js" type="text/javascript"></script>-->
            <!--        <script src="<?= media(); ?>/js/actions/functions_chatMessage.js" type="text/javascript"></script>-->
    <?php } ?>

<?php } ?>

<script src="<?= media(); ?>/js/dashboard.js" type="text/javascript"></script>
<script src="<?= media(); ?>/js/actions/<?= $data['page_functions_js'] ?>" type="text/javascript"></script>
<script>
        const base_url = "<?= base_url(); ?>";
</script>
</body>
</html>