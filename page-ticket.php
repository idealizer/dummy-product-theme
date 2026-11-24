<?php
/**
 * Template Name: Ticket (dummySUPPORT intake)
 * Ticket page — intake surface only; storage is dummySUPPORT.
 *
 * @package dummy-product
 */

$config = dummy_product_config();
$intake = $config['support_base'] . '/wp-json/dummysupport/v1/intake';
get_header();
?>
<div class="panel">
	<h1>Report a bug</h1>
	<p>Tickets go to <strong>dummySUPPORT</strong> — we eat our own dogfood. No Zendesk middleman.</p>
	<form id="dummy-ticket-form">
		<input type="text" name="hp" value="" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true" />
		<input type="hidden" name="product" value="<?php echo esc_attr( $config['slug'] ); ?>" />
		<p><label>Email<br /><input type="email" name="email" required style="width:100%" /></label></p>
		<p><label>Subject<br /><input type="text" name="subject" required style="width:100%" /></label></p>
		<p><label>Details<br /><textarea name="body" rows="6" required style="width:100%"></textarea></label></p>
		<button type="submit" class="cta" style="border:0;cursor:pointer">Send</button>
		<p id="dummy-ticket-msg" hidden></p>
	</form>
</div>
<script>
(function(){
  var form=document.getElementById('dummy-ticket-form');
  if(!form)return;
  form.addEventListener('submit',function(e){
    e.preventDefault();
    var fd=new FormData(form); var body={};
    fd.forEach(function(v,k){body[k]=v;});
    fetch(<?php echo wp_json_encode( $intake ); ?>,{
      method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body)
    }).then(function(r){return r.json()}).then(function(d){
      var m=document.getElementById('dummy-ticket-msg'); m.hidden=false;
      m.textContent=d.public_id ? ('Ticket '+d.public_id+' created. Thanks!') : (d.message||'Sent.');
      if(d.public_id) form.reset();
    }).catch(function(){
      var m=document.getElementById('dummy-ticket-msg'); m.hidden=false; m.textContent='Could not reach dummySUPPORT.';
    });
  });
})();
</script>
<?php
get_footer();
