<?php

/**
 * Extend meta box UI fields 
 */

class RWMB_PreviewCsv_Field extends RWMB_Field {
	public static function html( $meta, $field ) {
		// print_r($field);
		$html = '';
		if ( $field['meta_value'] == null || $field['meta_value'] == '' ) return "No meta_value.";
		$csv_files = rwmb_meta( $field['meta_value'] );
		if ( empty($csv_files) ) { 
			return "No files uploaded.";
		} else {
			// Process 
			$csv_file = reset($csv_files);
			// print_r($csv_file);
			if ( !file_exists( $csv_file['path'] ) ) return "CSV file not found.";
			
			$csv_data = array_map('str_getcsv', file($csv_file['path']));
			if ( count($csv_data) > 0 ) :
				$html .= "<table>";
				foreach( $csv_data as $row ) : 
					$html .= "<tr>";
					foreach( $row as $cell ) :
						$html .= sprintf('<td>%s</td>', esc_html($cell));
					endforeach;
					$html .= "</tr>";
				endforeach;
				$html .= "</table>";
				return $html;
			else :
				return "CSV file is empty.";
			endif;

		}
	}
}
