@if($layout->user()->permitted('rooms_observe_assignment'))						
	@if($layout->user()->permitted('rooms_assign'))
		<?php
			// output headers so that the file is downloaded rather than displayed
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=Szobabeosztas.csv');

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');

			// output the column headings
			fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
			fputcsv($output, array('Szoba', 'Lakó 1', 'Lakó 2', 'Lakó 3', 'Lakó 4'), ";");

			// loop over the rows, outputting them
			foreach($layout->room()->rooms() as $room){
				$row = [];
				array_push($row, (string)$room->room);
				foreach($layout->room()->getResidents($room->room) as $resident){
					array_push($row, $resident->name);
				}
				for($i = 0; $i < $layout->room()->getFreePlaceCount($room->room); $i++){
					array_push($row, "üres");
				}
				fputcsv($output, $row, ";");
			}
		?>
	@endif
@endif
