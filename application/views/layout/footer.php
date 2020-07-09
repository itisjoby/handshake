</div>

</body>
<!-- footer -->
<!-- ============================================================== -->
<footer class="footer text-center">
    All Rights Reserved by joby joseph. Designed and Developed by <a href="https://google.com?q=joby">joby joseph</a>.
</footer>
<!-- ============================================================== -->
<!-- End footer -->
<!-- ============================================================== -->



<script src="<?php echo base_url('third_party/files/cropping/js/cropper.js'); ?>"></script>
<script src="<?php echo base_url('third_party/files/cropping/js/main.js'); ?>"></script>



<!-- DataTables --> 
<script src="<?= base_url("third_party/files/datatables.net/js/jquery.dataTables.min.js"); ?>"></script> 
<!-- <script src="<?= base_url("third_party/files/datatables.net-bs/js/dataTables.bootstrap.min.js"); ?>"></script> -->
<!-- Select2 --> 
<script src="<?= base_url("third_party/files/select2/dist/js/select2.full.min.js"); ?>"></script> 
<!-- bootstrap datepicker --> 

<script src="<?= base_url("third_party/files/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"); ?>"></script> 


<!-- FormValidation.io -->
<script src="<?php echo base_url('third_party/files/formValidation/formValidation.min.js'); ?>"></script>
<script src="<?php echo base_url('third_party/files/formValidation/framework/bootstrap.min.js'); ?>"></script>
<!-- alertify -->
<script src="<?php echo base_url('third_party/files/alertifyjs/alertify.min.js'); ?>"></script>

<!--Custom JS-->

<script src="<?= base_url("js/common.js"); ?>"></script> 
<script type="text/javascript">
    var date_format = "<?php echo $this->config->item('js_date_format'); ?>";
</script>
<?php
if (isset($js_files) && is_array($js_files) && count($js_files) > 0) {
    $js_folder = 'js/';
    foreach ($js_files as $js_file) {
        if (file_exists($js_folder . $js_file)) {
            ?>
            <script type="text/javascript" src="<?php echo base_url($js_folder . $js_file); ?>"></script>
            <?php
        } else {
            ?>
            <script type="text/javascript">alert('File \'<?php echo $js_file; ?>\' not found in \'<?php echo $js_folder; ?>\' folder.');</script>
            <?php
        }
    }
}
?>
<!--End Custom JS-->


</body>

</html>