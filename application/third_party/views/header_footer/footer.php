</div>
</div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?php echo base_url(); ?>assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskInput.js"></script>

<?php
if (isset($datatables)) {
    echo '<script type="text/javascript" src="' . base_url() . 'assets/js/jquery.dataTables.min.js"></script>
          <script type="text/javascript" src="' . base_url() . 'assets/js/dataTables.bootstrap.min.js"></script>'
    ?>
    <script type="text/javascript">
    $(document).ready(function () {

        $('.dataTable').dataTable({
           
            /*"aProcessing": true,
            "aServerSide": true,
            "ajax": "<?php echo base_url(); ?>cargo/get_tables",*/

        "processing": true,
        "serverSide": true,
        "ajax": "<?php echo base_url(); ?>cargo/get_tables"

        });
        /* $('.dataTable').DataTable({
         'bProcessing': true,
         'bServerSide': true,
         'sAjaxSource': '<?php echo base_url($data_url); ?>',
         'sServerMethod': 'POST',
             
         'columns': [
         {'data': 'strCargo'},
         {'data': 'strDescricao'},
         {'data': 'MinSal'},
         {'data': 'MaxSal'}
         ],
         'fnServerData': function (sSource, aoData, fnCallback) {
         $.ajax({
         dataType: 'json',
         type: 'post',
         url: sSource,
         data: aoData,
         success: fnCallback,
         });
         }
         });*/
    });</script>              
<?php } ?>

<script src="<?php echo base_url(); ?>assets/js/js_aplicacao/geral.js"></script>
<script type="text/javascript">
    $("a[href*='" + location.pathname + "']").parent().addClass("active");
</script>
</body>
</html>