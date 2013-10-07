<?php

# =======================
# Flat file DB Functions:
# -----------------------
function load_file($what, $which) {
	$handle = fopen($which, 'r');
	if ($handle) {
		while (!feof($handle)) {
			$buffer = fgets($handle, 4096);
			if (!empty($buffer)) {
				$line_array = explode('||', $buffer);
				if (!empty($line_array[1])) {
					$cleaned_value = str_replace("[|]", "\n", trim($line_array[1]));
				}
				$GLOBALS[$what][$line_array[0]] = $cleaned_value;
			}
		}
		fclose($handle);
  }
}

function write_file($which_array, $to_which_file) {

	if (!empty($to_which_file) && !empty($which_array)) {

		$handle = fopen($to_which_file, 'w');
		if ($handle) {
			// Sort the array by keys
			ksort($which_array);
			// Clean up the entries. Leave HTML tags but encode accents etc.
			$trans = get_html_translation_table(HTML_ENTITIES);
			unset($trans[array_search('&amp;', $trans)]);
			unset($trans[array_search('&lt;', $trans)]);
			unset($trans[array_search('&gt;', $trans)]);
			unset($trans[array_search('&quot;', $trans)]);
			unset($trans[array_search('&#039;', $trans)]);
			$trans["\r\n"] = '[|]';
			$trans["\n"] = '[|]';
			$trans["\r"] = '[|]';

			// go through the array and write to file:
			foreach($which_array as $content_key => $content_value) {
				$encoded_content_value = strtr(stripcslashes($content_value), $trans);
				$what_to_write = $content_key.'||'.$encoded_content_value."\n";
				fwrite($handle, $what_to_write);
			}
			fclose($handle);
			return TRUE;

		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}
# =======================


?>
