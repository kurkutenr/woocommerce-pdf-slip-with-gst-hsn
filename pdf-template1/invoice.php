<?php do_action( 'wpo_wcpdf_before_document', $this->type, $this->order ); ?>

<div class="widthInvoice">
	<table class="head container">
		<tr>
			<td class="header">
			<?php
			if( $this->has_header_logo() ) {
				$this->header_logo();
			} else {
				echo $this->get_title();
			}
			?>
			</td>
			<td class="shop-info">
				<div class="shop-name"><h3><?php $this->shop_name(); ?></h3></div>
				<div class="shop-address"><?php $this->shop_address(); ?></div>
			</td>
		</tr>
	</table>

	<h1 class="document-type-label">
	<?php if( $this->has_header_logo() ) echo $this->get_title(); ?>
	</h1>

	<?php do_action( 'wpo_wcpdf_after_document_label', $this->type, $this->order ); ?>

	<table class="order-data-addresses">
		<tr>
			<td class="address billing-address">
				<!-- <h3><?php _e( 'Billing Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3> -->
				<?php do_action( 'wpo_wcpdf_before_billing_address', $this->type, $this->order ); ?>
				<?php $this->billing_address(); ?>
				<?php do_action( 'wpo_wcpdf_after_billing_address', $this->type, $this->order ); ?>
				<?php if ( isset($this->settings['display_email']) ) { ?>
				<div class="billing-email"><?php $this->billing_email(); ?></div>
				<?php } ?>
				<?php if ( isset($this->settings['display_phone']) ) { ?>
				<div class="billing-phone"><?php $this->billing_phone(); ?></div>
				<?php } ?>
			</td>
			<td class="address shipping-address">
				<?php if ( isset($this->settings['display_shipping_address']) && $this->ships_to_different_address()) { ?>
				<h3><?php _e( 'Ship To:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
				<?php do_action( 'wpo_wcpdf_before_shipping_address', $this->type, $this->order ); ?>
				<?php $this->shipping_address(); ?>
				<?php do_action( 'wpo_wcpdf_after_shipping_address', $this->type, $this->order ); ?>
				<?php } ?>
			</td>
			<td class="order-data">
				<table>
					<?php do_action( 'wpo_wcpdf_before_order_data', $this->type, $this->order ); ?>
					<?php if ( isset($this->settings['display_number']) ) { ?>
					<tr class="invoice-number">
						<th><?php _e( 'Invoice Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
						<td><?php $this->invoice_number(); ?></td>
					</tr>
					<?php } ?>
					<?php if ( isset($this->settings['display_date']) ) { ?>
					<tr class="invoice-date">
						<th><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
						<td><?php $this->invoice_date(); ?></td>
					</tr>
					<?php } ?>
					<tr class="order-number">
						<th><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
						<td><?php $this->order_number(); ?></td>
					</tr>
					<tr class="order-date">
						<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
						<td><?php $this->order_date(); ?></td>
					</tr>
					<tr class="payment-method">
						<th><?php _e( 'Payment Method:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
						<td><?php $this->payment_method(); ?></td>
					</tr>
					<?php do_action( 'wpo_wcpdf_after_order_data', $this->type, $this->order ); ?>
				</table>			
			</td>
		</tr>
	</table>

	<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>

	<?php 
		$tax_count = 0; 
		$tax_slabs = array();
		$tax_labels = array();
		$price_subtotal = 0;
	?>
	<table class="order-details">
		<thead>
			<tr>
				<th class="product"><?php _e('Product', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
				<th class="hsn"><?php _e('HSN/SAC', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
				<th class="quantity"><?php _e('Quantity', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
				<th class="price"><?php _e('Price', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $items = $this->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) : ?>
			<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $this->type, $this->order, $item_id ); ?>">
				<td class="product">
					<?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
					<span class="item-name"><?php echo $item['name']; ?></span>
					<?php do_action( 'wpo_wcpdf_before_item_meta', $this->type, $item, $this->order  ); ?>
					<span class="item-meta"><?php echo $item['meta']; ?></span>
					<dl class="meta">
						<?php $description_label = __( 'SKU', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
						<!-- <?php //if( !empty( $item['sku'] ) ) : ?><dt class="sku"><?php //_e( 'SKU:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="sku"><?php //echo $item['sku']; ?></dd><?php //endif; ?> -->
						<?php if( !empty( $item['weight'] ) ) : ?><dt class="weight"><?php _e( 'Weight:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="weight"><?php echo $item['weight']; ?><?php echo get_option('woocommerce_weight_unit'); ?></dd><?php endif; ?>
						<?php if( !empty( $item['dimensions'] ) ) : ?><dt class="weight"><?php _e( 'Dimensions:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="weight"><?php echo $item['dimensions']; ?><?php echo get_option('woocommerce_dimensions_unit'); ?></dd><?php endif; ?>
						
						
							<?php if ( !empty($item['product']) )
								$p_attributes = $item['product']->get_attributes();

								foreach( $p_attributes as $attKey => $attValue ) {
							?>
								
								<dt class="weight"><?php 	echo $attValue['name'] ; ?> : </dt>
								<dd class="weight"><?php 	echo  $attValue['options'][0] ; ?></dd>

							<?php } ?>
							
							<?php if ( !empty($item ) )
								$attributes = $item['product']->get_attributes();
								$order_id =$this->get_order_number();
								$order = wc_get_order($order_id);
								$tax_count = count($order->get_items('tax'));
							?>
						
					</dl>
					<?php do_action( 'wpo_wcpdf_after_item_meta', $this->type, $item, $this->order  ); ?>
				</td>
				<td class="hsn"><?php if ( !empty($item['product'] ) ) echo $item['product']->get_meta('hsn_prod_id'); ?></td>
				<td class="quantity"><?php echo $item['quantity']; ?></td>
				<td class="price"><?php echo $item['order_price']; ?></td>
			</tr>
			<?php endforeach; endif; ?>

			

		</tbody>

	</table>
	<br>
	<table class="order-details">
		<thead>
			<tr>
				<td class=""><?php _e('HSN/SAC', 'woocommerce-pdf-invoices-packing-slips' ); ?></td>
				<td class=""><?php _e('Tax Value', 'woocommerce-pdf-invoices-packing-slips' ); ?></td>
				<?php if ($tax_count < 2) { ?>
					<td class=""><?php _e('Inter State Tax', 'woocommerce-pdf-invoices-packing-slips' ); ?></td>
					<td class=""><?php _e('', 'woocommerce-pdf-invoices-packing-slips' ); ?></td>
				<?php } else { ?> 
					<td class=""><?php _e('Central Tax', 'woocommerce-pdf-invoices-packing-slips' ); ?></td>
					<td class=""><?php _e('State Tax', 'woocommerce-pdf-invoices-packing-slips' ); ?></td>
				<?php } ?>
				
				<td class=""><?php _e('Total Tax', 'woocommerce-pdf-invoices-packing-slips' ); ?></td>
			</tr>
		</thead>
		<tbody>
			<?php $items = $this->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) : ?>
			
			<?php 
				// print_r($item);
				$tax_total = 0;
				

				if ( !empty($item) )
					$price_subtotal = $item['line_subtotal'];
					$order_id =$this->get_order_number();
					$order = wc_get_order($order_id);
					foreach( $order->get_items( 'tax' ) as $item_id => $item_tax ){
						## -- Get all protected data in an accessible array -- ##
					
						// $tax_data = $item_tax->get_data(); // Get the Tax data in an array
						// $item_type = $item_tax->get_type(); // Line item type
						// $item_name = $item_tax->get_name(); // Line item name
						// $rate_code = $item_tax->get_rate_code(); // Tax rate code
						
						// $tax_rate_id = $item_tax->get_rate_id(); // Tax rate ID
						// $compound = $item_tax->get_compound(); // Tax compound
						$tax_amount_total = $item_tax->get_tax_total(); // Tax rate total
						$tax_rate_label = $item_tax->get_label(); // Tax label
						// $tax_shipping_total = $item_tax->get_shipping_tax_total(); // Tax shipping total

						$tax_total += wc_format_decimal($tax_amount_total, 2);
						array_push($tax_slabs, wc_format_decimal($tax_amount_total, 2));
						array_push($tax_labels, array($tax_rate_label, wc_price(wc_format_decimal($tax_amount_total, 2))));
						// echo $tax_rate_label . " - ". wc_format_decimal($tax_amount_total, 2) . "<br>"; 
					}
			?>
			

			<tr class="">
				<td class=""><?php if ( !empty($item['product'] ) ) echo $item['product']->get_meta('hsn_prod_id'); ?></td>
				<td class=""><?php echo $item['line_subtotal']; ?></td>
				<?php

				if(sizeof($tax_slabs) <= 1) {
				?>
					<td class=""><?php echo wc_price($tax_slabs[0]); ?></td>
					<td class=""><?php echo ""; ?></td>
				<?php } else { ?>
					<td class=""><?php echo wc_price($tax_slabs[0]); ?></td>
					<td class=""><?php echo wc_price($tax_slabs[1]); ?></td>
				<?php }  ?>

				
				<td class=""><?php echo wc_price($tax_total); ?></td>
			</tr>
			<tr class="total">
				<td class=""><?php _e('Total', 'woocommerce-pdf-invoices-packing-slips' ); ?></td>
				<td class=""><?php echo $item['line_subtotal']; ?></td>
				<?php
				if(sizeof($tax_slabs) <= 1) {
				?>
					<td class=""><?php echo wc_price($tax_slabs[0]); ?></td>
					<td class=""><?php echo ""; ?></td>
				<?php } else { ?>
					<td class=""><?php echo wc_price($tax_slabs[0]); ?></td>
					<td class=""><?php echo wc_price($tax_slabs[1]); ?></td>
				<?php }  ?>

				<td class=""><?php echo wc_price($tax_total); ?></td>
			</tr>
			<?php endforeach; endif; ?>
		</tbody>

		<tfoot>
			<tr class="no-borders">
				<td class="no-borders" colspan="2">
					<div class="customer-notes">
						<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->type, $this->order ); ?>
						<?php if ( $this->get_shipping_notes() ) : ?>
							<h3><?php _e( 'Customer Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
							<?php $this->shipping_notes(); ?>
						<?php endif; ?>
						<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->type, $this->order ); ?>
					</div>				
				</td>
				<td class="no-borders" colspan="3">
					<table class="totals">
						<tfoot>
							<?php $final_total = $this->get_woocommerce_totals();
							?>

							<tr class="cart_subtotal ">
								<td class="no-borders"></td>
								<th class="description"><?php echo $final_total["cart_subtotal"]['label']; ?></th>
								<td class="price"><span class="totals-price"><?php echo $price_subtotal; ?></span></td>
							</tr>
							<?php foreach( $tax_labels as $key => $taxslab ) : ?>

							<tr class="cart_subtotal">
								<td class="no-borders"></td>
								<th class="description"><?php echo $taxslab[0]; ?></th>
								<td class="price"><span class="totals-price"><?php echo $taxslab[1]; ?></span></td>
							</tr>
							<?php endforeach; ?>

							<tr class="order_total">
								<td class="no-borders"></td>
								<th class="description"><?php echo $final_total["order_total"]['label']; ?></th>
								<td class="price"><span class="totals-price"><?php echo  $final_total["cart_subtotal"]['value']; ?></span></td>
							</tr>
						</tfoot>
					</table>
				</td>
			</tr>
			<tr class="no-borders">
				<td class="no-borders" colspan="3">
					<table>
						<tfoot>
							<tr >
								<td class="back_hd"> Terms </td>
							</tr>
							<tr class="">
								<td class="no-borders"> 
										Terms : Payment 100% in advance <br>

								</td>
							</tr>
						</tfoot>
					</table>
				</td>

				<td class="no-borders" colspan="2">
					<table class="totals">
						
					</table>
				</td>
			</tr>
		</tfoot>
	</table>


	<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>

	<?php if ( $this->get_footer() ): ?>
	<div id="footer">
		<?php $this->footer(); ?>
	</div><!-- #letter-footer -->
	<?php endif; ?>
	<?php do_action( 'wpo_wcpdf_after_document', $this->type, $this->order ); ?>
</div>
