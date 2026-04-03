<!-- Boostrap JavaScript -->
<script src="../js/jquery.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/all.min.js"></script>

<!-- JS CDN link For Datatables to use search and pagination functionality -->
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

<!-- For Datatables -->
<script>
    $(document).ready(function() {
        $('#dataTableID').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search Here...",
            }
        });
    });
</script>

</body>

</html>