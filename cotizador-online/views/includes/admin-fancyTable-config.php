<script src="views/scripts/fancyTable.min.js"></script>
<script>
$(document).ready(function(){
	$('.tabla_filtrable').fancyTable({
		//sortColumn: 0,
		//sortOrder: 'asc',
		sortable: true,
		pagination: <?php echo $pagination;?>,
		searchable: true,
		globalSearch: <?php echo $globalSearch;?>,
		paginationClass: '',
		paginationClassActive: 'active',
		pagClosest: 3,
		perPage: <?php echo $perPage;?>,
		inputStyle: '',
		inputPlaceholder: 'Buscar...',
	});
});
</script>