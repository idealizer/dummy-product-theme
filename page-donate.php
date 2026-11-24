<?php
/**
 * Template Name: Donate
 *
 * @package dummy-product
 */

$config = dummy_product_config();
get_header();
?>
<div class="panel">
	<h1>Donate</h1>
	<p>No Pro tier. No upsell. If <?php echo esc_html( $config['name'] ); ?> helped you, donate whatever feels of fair value — free if you need it that way. Donors get a supporter code that permanently hides the in-plugin banner.</p>
	<form id="dummy-donate-form">
		<p><label>Email (for your supporter code)<br /><input type="email" name="email" required style="width:100%" /></label></p>
		<p><label>Amount (EUR)<br /><input type="number" name="amount_eur" min="0.5" step="0.5" placeholder="Your call" required style="width:100%" /></label></p>
		<button type="submit" class="cta" style="border:0;cursor:pointer">Donate with Stripe</button>
		<p id="dummy-donate-msg" hidden></p>
	</form>
	<?php if ( isset( $_GET['success'], $_GET['session_id'] ) ) : ?>
		<div class="panel" style="margin-top:1rem;background:#e8f7ee">
			<p>Thanks! Fetching your supporter code…</p>
			<pre id="dummy-donor-token">…</pre>
		</div>
		<script>
		fetch(<?php echo wp_json_encode( esc_url_raw( rest_url( 'dummy-product/v1/donate/token?session_id=' . sanitize_text_field( wp_unslash( $_GET['session_id'] ) ) ) ) ); ?>)
			.then(function(r){return r.json()}).then(function(d){
				var el=document.getElementById('dummy-donor-token');
				el.textContent=d.token||(d.message||JSON.stringify(d));
			});
		</script>
	<?php endif; ?>
</div>
<script>
(function(){
  var form=document.getElementById('dummy-donate-form');
  if(!form)return;
  form.addEventListener('submit',function(e){
    e.preventDefault();
    var fd=new FormData(form);
    var eur=parseFloat(fd.get('amount_eur'));
    if(!eur||eur<0.5){
      var m=document.getElementById('dummy-donate-msg'); m.hidden=false; m.textContent='Enter whatever feels fair (min 0.50 EUR for Stripe).';
      return;
    }
    fetch(<?php echo wp_json_encode( esc_url_raw( rest_url( 'dummy-product/v1/donate/checkout' ) ) ); ?>,{
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body:JSON.stringify({email:fd.get('email'), amount:Math.round(eur*100), currency:'eur'})
    }).then(function(r){return r.json()}).then(function(d){
      if(d.url){ location.href=d.url; return; }
      var m=document.getElementById('dummy-donate-msg'); m.hidden=false; m.textContent=d.message||'Checkout unavailable (configure DUMMY_STRIPE_SECRET).';
    }).catch(function(){
      var m=document.getElementById('dummy-donate-msg'); m.hidden=false; m.textContent='Could not start checkout.';
    });
  });
})();
</script>
<?php
get_footer();
