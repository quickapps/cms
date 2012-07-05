<?php
/**
 * Advanced search form
 * This element is rendered as part of search result (Node.View/Node/search.ctp).
 *
 * @package	 QuickApps.Plugin.Node.View.Elements
 * @author	 Christopher Castro
 */
?>

<div id="search-advanced">

	<?php echo $this->Form->create('Search', array('url' => '/search/')); ?>

			<?php echo $this->Html->useTag('fieldsetstart', "<span id=\"toggle-search_advanced\">" . __t('Search') . "</span>"); ?>

				<div id="search_advanced">
					<div class="text-criterias">
						<?php echo $this->Form->input('or', array('label' => __t('Containing any of the words'))); ?>
						<?php echo $this->Form->input('phrase', array('label' => __t('Containing the phrase'))); ?>
						<?php echo $this->Form->input('negative', array('label' => __t('Containing none of the words'))); ?>
					</div>

					<div class="check-criterias">
						<?php
							echo $this->Form->input('type',
								array(
									'label' => __t('Only of the type(s)'),
									'type' => 'select',
									'multiple' => 'checkbox',
									'options' => $nodeTypes
								)
							);
						 ?>

						<!-- types | languages -->

						 <?php
							echo $this->Form->input('language',
								array(
									'label' => __t('Languages'),
									'type' => 'select',
									'multiple' => 'checkbox',
									'options' => $languages
								)
							);
						?>
					</div>

					<?php echo $this->Form->submit(__t('Search')); ?>

				</div>
			<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Form->end(); ?>

</div> <!--/ search-advanced -->