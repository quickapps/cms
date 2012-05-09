<!-- default offline error -->
<h2><?php echo $name; ?></h2>
<p class="error">
	<h4><?php echo $this->Layout->hooktags(__t(Configure::read('Variable.site_maintenance_message'))); ?></h4>
</p>