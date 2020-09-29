# woocommerce-pdf-slip-with-gst-hsn

This is customized woocommerce pdf invoice slip generator with the details of GST tax and HSN number.

To use your own template with  "woocommerce pdf invoices & packing slips" plug-in. 

Copy all the files to your (child) theme in
wp-content/themes/<themename>/woocommerce/pdf/<yourtemplatename>


If you want only specific fields in invoice - use below code and add required attributes the list

///////////////////////////////////////////////////

$requiredAttr = array("HSN");



foreach( $p_attributes as $attKey => $attValue ) {

	if (in_array($attKey, $requiredAttr)) {
?>
	
	<dt class="weight"><?php 	echo $attValue['name'] ; ?> : </dt>
	<dd class="weight"><?php 	echo  $attValue['options'][0] ; ?></dd>


<?php 

	}} 

?>


///////////////////////////////////////////////////
