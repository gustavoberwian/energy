<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
				<section role="main" class="content-body">
					<!-- start: page -->
					<section class="card">
						<div class="card-body">
							<div class="invoice">
								<header class="clearfix">
									<div class="row">
										<div class="col-sm-6 mt-3">
											<h2 class="h2 mt-0 mb-1 text-dark font-weight-bold">INVOICE</h2>
											<h4 class="h4 m-0 text-dark font-weight-bold">#76598345</h4>
										</div>
										<div class="col-sm-6 text-right mt-3 mb-3">
											<address class="ib mr-5">
												Okler Themes Ltd
												<br/>
												123 Porto Street, New York, USA
												<br/>
												Phone: +12 3 4567-8901
												<br/>
												okler@okler.net
											</address>
											<div class="ib">
												<img src="<?php echo base_url('assets/img/invoice-logo.png'); ?>" alt="Easymeter" />
											</div>
										</div>
									</div>
								</header>
								<div class="bill-info">
									<div class="row">
										<div class="col-md-6">
											<div class="bill-to">
												<p class="h5 mb-1 text-dark font-weight-semibold">To:</p>
												<address>
													Envato
													<br/>
													121 King Street, Melbourne, Australia
													<br/>
													Phone: +61 3 8376 6284
													<br/>
													info@envato.com
												</address>
											</div>
										</div>
										<div class="col-md-6">
											<div class="bill-data text-right">
												<p class="mb-0">
													<span class="text-dark">Invoice Date:</span>
													<span class="value">05/20/2017</span>
												</p>
												<p class="mb-0">
													<span class="text-dark">Due Date:</span>
													<span class="value">06/20/2017</span>
												</p>
											</div>
										</div>
									</div>
								</div>
							
								<table class="table table-responsive-md invoice-items">
									<thead>
										<tr class="text-dark">
											<th id="cell-id"     class="font-weight-semibold">#</th>
											<th id="cell-item"   class="font-weight-semibold">Item</th>
											<th id="cell-desc"   class="font-weight-semibold">Description</th>
											<th id="cell-price"  class="text-center font-weight-semibold">Price</th>
											<th id="cell-qty"    class="text-center font-weight-semibold">Quantity</th>
											<th id="cell-total"  class="text-center font-weight-semibold">Total</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>123456</td>
											<td class="font-weight-semibold text-dark">Porto HTML5 Template</td>
											<td>Multipourpouse Website Template</td>
											<td class="text-center">$14.00</td>
											<td class="text-center">2</td>
											<td class="text-center">$28.00</td>
										</tr>
										<tr>
											<td>654321</td>
											<td class="font-weight-semibold text-dark">Tucson HTML5 Template</td>
											<td>Awesome Website Template</td>
											<td class="text-center">$17.00</td>
											<td class="text-center">1</td>
											<td class="text-center">$17.00</td>
										</tr>
									</tbody>
								</table>
							
								<div class="invoice-summary">
									<div class="row justify-content-end">
										<div class="col-sm-4">
											<table class="table h6 text-dark">
												<tbody>
													<tr class="b-top-0">
														<td colspan="2">Subtotal</td>
														<td class="text-left">$73.00</td>
													</tr>
													<tr>
														<td colspan="2">Shipping</td>
														<td class="text-left">$0.00</td>
													</tr>
													<tr class="h4">
														<td colspan="2">Grand Total</td>
														<td class="text-left">$73.00</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="text-right mr-4">
								<a href="#" target="_blank" class="btn btn-primary ml-3"><i class="fas fa-print"></i> Imprimir</a>
							</div>
						</div>
					</section>
					<!-- end: page -->
				</section>