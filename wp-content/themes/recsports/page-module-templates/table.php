<?
// Get field as specified by the table plugin.
$acf_table = new acf_field_table();
$table = $acf_table->format_value($meta[$pcb . "table"][0], 0, array());

$tophead = (isset($meta[$pcb . "use_header_on_top"]) && $meta[$pcb . "use_header_on_top"][0] ? "table__table--topheader " : " ");
$lefthead = (isset($meta[$pcb . "use_header_on_left"]) && $meta[$pcb . "use_header_on_left"][0] ? "table__table--leftheader " : " ");
$cellalign = "table__table--align" . $meta[$pcb . "data_cell_alignment"][0];
$tablewidth = " table__table--width" . ((isset($meta[$pcb . "table_width"]) && $meta[$pcb . "table_width"][0] == "shrinkwrap") ? "shrinkwrap" : "stretch");
$leftheaderwidth = " table__table--leftheaderalign" . ((isset($meta[$pcb . "left_header_cell_alignment"]) && $meta[$pcb . "left_header_cell_alignment"][0] == "left") ? "left" : "right");

$firstrow = true;
$widths = (isset($meta[$pcb . "column_widths"]) && $meta[$pcb . "column_widths"][0] ? explode(",", $meta[$pcb . "column_widths"][0]) : array());

$widths = array_map('floatval',$widths);


echo '<table class="table__table ' . $tophead . $lefthead . $cellalign . $tablewidth . $leftheaderwidth . '">';
	if ( $table['header'] ) {
		echo '<thead>';
		echo '<tr>';
			foreach ( $table['header'] as $th ) {
				echo '<th>';
				echo $th['c'];
				echo '</th>';
			}
			$firstrow = false;
			echo '</tr>';
			echo '</thead>';
		}
		echo '<tbody>';
		foreach ( $table['body'] as $tr ) {
			echo '<tr>';
			$count = 0;
			foreach ( $tr as $td ) {
				if($firstrow && isset($widths[$count]) && $widths[$count] > 0) {
					echo '<td style="width:' . $widths[$count] . '%;">';
					++$count;
				} else {
					echo '<td>';
				}
				echo $td['c'];
				echo '</td>';
			}
			$firstrow = false;
			echo '</tr>';
		}
	echo '</tbody>';
echo '</table>';
?>
