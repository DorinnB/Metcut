<script language="javascript">
$(document).ready( function () {
    $('#table_id').DataTable({
		"iDisplayLength": 200,
		columnDefs: [ {
            targets: [ 0 ],
            orderData: [ 0, 1 ]
        }, {
            targets: [ 1 ],
            orderData: [ 1, 0 ]
        }, {
            targets: [ 4 ],
            orderData: [ 4, 0 ]
        } ]
	});

} );
</script>

<form action="index.php?page=tbljobs_maj" method="POST">
    <div>
        	<button type="submit">Mise à jour des données</button>
    </div>

<?php

$titre=array('id_statuts',	'customer',	'job',	'split',	'PO_instructions',	'id_type_essais',	'id_condition_temps',	'material',	'id_material',	'drawing',	'comments',	'nb_specimen',	'tooling',	'MRI_req',	'MFG_qty',	'nb_MRI',	'sub_C',	'type_machine',	'nb_test_MRSAS',	'ordre',	'blanks_reception',	'blanks_shipment',	'specimen_leadtime',	'specimen_reception',	'test_start',	'test_end',	'test_leadtime',	'estimated_turn_over',	'estimated_testing',	'invoiced_turn_over',	'invoiced_testing');
$titresql=array('id_statuts',	'customer',	'job',	'split',	'PO_instructions',	'id_type_essais',	'id_condition_temps',	'material',	'id_material',	'drawing',	'comments',	'nb_specimen',	'tooling',	'MRI_req',	'MFG_qty',	'nb_MRI',	'sub_C',	'type_machine',	'nb_test_MRSAS',	'ordre',	'blanks_reception',	'blanks_shipment',	'specimen_leadtime',	'specimen_reception',	'test_start',	'test_end',	'test_leadtime',	'estimated_turn_over',	'estimated_testing',	'invoiced_turn_over',	'invoiced_testing');

$req_tbljobs = $db->query("SELECT id_tbljob,	id_statuts,	customer,	job,	split,	PO_instructions,	id_type_essais,	id_condition_temps,	material,	id_material,	drawing,	comments,	nb_specimen,	tooling,	MRI_req,	MFG_qty,	nb_MRI,	sub_C,	type_machine,	nb_test_MRSAS,	ordre,	blanks_reception,	blanks_shipment,	specimen_leadtime,	specimen_reception,	test_start,	test_end,	test_leadtime,	estimated_turn_over,	estimated_testing,	invoiced_turn_over,	invoiced_testing
	FROM tbljobs;");
	while ($tbl_req = mysqli_fetch_array($req_tbljobs)) {
		$tbljobs[]=$tbl_req;
	}	

echo '
<table id="table_id" class="display" cellspacing="0" width="100%">
	<thead>
		<tr>';	
		for($i=0; $i<count($titre); $i++){
			echo '<th>'.$titre[$i].'</th>';
		}
	echo '</tr>
	</thead>
	<tbody>';
							


	


	if ($tbljobs) {
		for($k=0;$k < count($tbljobs);$k++)	{
			echo '<tr style="font-size:0px">';
			
			$color="white";
			$color=($tbljobs[$k]['id_statuts']>=00)? "#FA5858" : $color;
			$color=($tbljobs[$k]['id_statuts']>=10)? "#F79F81" : $color;
			$color=($tbljobs[$k]['id_statuts']>=20)? "#FF8000" : $color;
			$color=($tbljobs[$k]['id_statuts']>=50)? "#F3F781" : $color;
			$color=($tbljobs[$k]['id_statuts']>=70)? "#9FF781" : $color;
			$color=($tbljobs[$k]['id_statuts']>=80)? "#04B404" : $color;
			
						
			for($j=0;$j < count($titresql);$j++)	{
				echo '<td bgcolor="'.$color.'">
				<input id="row-'.$k.$j.'1-age" name="'.$tbljobs[$k]['id_tbljob']."-".$titresql[$j].'" value="'.$tbljobs[$k][$titresql[$j]].'" type="text">'.$tbljobs[$k][$titresql[$j]].'</td>
				';
			}
			echo '</tr>
			';
		}
	}

	

	
	
	
	?>

	</tbody>
</table>

</form>




