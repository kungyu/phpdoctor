<?php
/*
PHPDoctor: The PHP Documentation Creator
Copyright (C) 2004 Paul James <paul@peej.co.uk>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/** This generates the HTML API documentation for each global function.
 *
 * @package PHPDoctor.Doclets.Standard
 */
class functionWriter extends htmlWriter {

	/** Build the function definitons.
	 *
	 * @param doclet doclet
	 */
	function functionWriter(&$doclet) {
	
		parent::htmlWriter($doclet);
		
		$this->_id = 'definition';

		$rootDoc =& $this->_doclet->rootDoc();

		foreach($rootDoc->packages() as $packageName => $package) {

			$this->_sections[0] = array('title' => 'Overview', 'url' => 'overview-summary.html');
			$this->_sections[1] = array('title' => 'Package', 'url' => $package->asPath().'/package-summary.html');
			$this->_sections[2] = array('title' => 'Function', 'selected' => TRUE);
			$this->_sections[3] = array('title' => 'Use');
			$this->_sections[4] = array('title' => 'Tree', 'url' => 'overview-tree.html');
			$this->_sections[5] = array('title' => 'Index', 'url' => 'index-files/index-1.html');
		
			$this->_depth = $package->depth() + 1;

			ob_start();

			echo "<hr />\n\n";

			echo "<h1>Functions</h1>\n\n";
					
			echo "<hr />\n\n";
					
			$functions =& $package->functions();
				
			if ($functions) {
				echo '<a name="summary_function"></a>', "\n";
				echo '<table class="title">', "\n";
				echo '<tr><th colspan="2" class="title">Function Summary</th></tr>', "\n";
				foreach($functions as $function) {
					$textTag =& $function->tags('@text');
					if ($textTag) {
						$description =& $this->_splitComment($textTag->text());
					} else {
						$description[0] = NULL;
					}
					echo "<tr>\n";
					echo '<td class="type">', $function->modifiers(FALSE), ' ', $function->returnTypeAsString(), "</td>\n";
					echo '<td class="description">';
					echo '<p class="name"><a href="#'.$function->name().'">', $function->name(), '</a>', $function->flatSignature(), '</p>';
					echo '<p class="description">'.$description[0].'</p>';
					echo "</td>\n";
					echo "</tr>\n";
				}
				echo "</table>\n\n";

				echo '<a name="detail_function"></a>', "\n";
				echo '<table class="detail">', "\n";
				echo '<tr><th colspan="2" class="title">Function Detail</th></tr>', "\n";
				echo "</table>\n";
				foreach($functions as $function) {
					$textTag =& $function->tags('@text');
					if ($textTag) {
						$description =& $this->_splitComment($textTag->text());
					} else {
						$description[0] = NULL;
					}
					echo '<a name="', $function->name(),'"></a>', "\n";
					echo '<h2>', $function->name(), "</h2>\n";
					echo '<code>', $function->modifiers(), ' ', $function->returnTypeAsString(), ' <strong>';
					echo $function->name(), '</strong>', $function->flatSignature();
					echo "</code>\n";
					echo '<div class="details">', "\n";
					echo '<p>', $description[0], "</p>\n";
					if (isset($description[1])) echo '<p>', $description[1], "</p>\n";
					echo "</div>\n\n";
					$this->_processTags($function->tags());
					echo "<hr />\n\n";
				}
			}

			$this->_output = ob_get_contents();
			ob_end_clean();

			$this->_write($package->asPath().'/package-functions.html', 'Functions', TRUE);
		}
	
	}

}

?>