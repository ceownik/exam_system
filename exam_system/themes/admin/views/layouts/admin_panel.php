<?php $this->beginContent('//layouts/main'); ?>

	<!-- Tray -->
	<div id="tray" class="box">

		<p class="f-left box">

			<!-- Switcher -->
			<span class="f-left" id="switcher">
				<a href="#" rel="1col" class="styleswitch ico-col1" title="Display one column"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/switcher-1col.gif" alt="1 Column" /></a>
				<a href="#" rel="2col" class="styleswitch ico-col2" title="Display two columns"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/switcher-2col.gif" alt="2 Columns" /></a>
			</span>

			<!-- page title -->
			<span class="f-left"><strong><?php echo CHtml::encode($this->pageTitle); ?></strong></span>
			
			
			
		</p>

		<p class="f-right">Użytkownik: <strong><a href="<?php echo "/admin/users/view/id/".Yii::app()->user->id; ?>"><?php echo Yii::app()->user->name; ?></a></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		<strong><?php echo CHtml::link('Wyloguj', array('/admin/logout'), array('id'=>'logout')); ?></strong></p>

	</div> <!--  /tray -->

	<hr class="noscreen" />

	
	
	<!-- Columns -->
	<div id="cols" class="box">

		<!-- Aside (Left Column) -->
		<div id="aside" class="box">

			<div class="padding box">

				<!-- Logo (Max. width = 200px) -->
				<p id="logo"><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/app-logo.png" alt="Our logo" title="Visit Site" /></a></p>
				
				
				<!-- Search -->
				<form action="#" method="get" id="search" style="display:none">
					<fieldset>
						<legend>Search</legend>

						<p><input type="text" size="17" name="" class="input-text" />&nbsp;<input type="submit" value="OK" class="input-submit-02" /><br />
						<a href="javascript:toggle('search-options');" class="ico-drop">Advanced search</a></p>

						<!-- Advanced search -->
						<div id="search-options" style="display:none;">

							<p>
								<label><input type="checkbox" name="" checked="checked" /> Option I.</label><br />
								<label><input type="checkbox" name="" /> Option II.</label><br />
								<label><input type="checkbox" name="" /> Option III.</label>
							</p>

						</div> <!-- /search-options -->

					</fieldset>
				</form>

				
				
				
				<div id="mainmenu">
					<?php $items = array(
							array('label'=>'Strona główna', 'url'=>array('/admin/index')),
						);
					foreach( Yii::app()->modules as $id => $m )
					{
						// if module is a back-end module
						if(isset($m['isBackEnd']) && $m['isBackEnd'])
						{
							$items[] = array(
								'label'=>Yii::t('admin', $id.'_menu_label'),
								'url'=>array('/admin/'.$id),
								'visible' => Yii::app()->user->checkAccess($id)
							);
						}
					}
					
					$this->widget('zii.widgets.CMenu',array(
						'items'=>$items,
					)); 				
					?>
				</div><!-- mainmenu -->
				
				
				
				
				<!-- Create a new project -->
				<p id="btn-create" class="box" style="display:none; "><a href="#"><span>Create a new project</span></a></p>

			</div> <!-- /padding -->

			
			

		</div> <!-- /aside -->

		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box">
			
			
			<h1><?php if($this->headerTitle){echo $this->headerTitle;}else{if(isset($this->module->id)){echo $this->module->id;}else{echo $this->id;}} ?></h1>
			
			<?php if( isset($this->module) && !empty($this->module->menuItems) ) { ?>
				<div id="shortcuts">
					<?php
					$this->widget('zii.widgets.CMenu',array(
						'items' => $this->module->menuItems
					)); ?>
				</div>
			<?php } ?>
			
			<div id="flash-messages">
				<?php
					foreach(Yii::app()->user->getFlashes() as $key => $message) {
						echo '<div class="msg ' . $key . '">' . $message . "</div>\n";
					}
				?>
			</div>
			
			<div id="content-inner">
				<?php echo $content; ?>
			</div><!-- content -->

		</div> <!-- /content -->

	</div> <!-- /cols -->

	<hr class="noscreen" />






<?php $this->endContent(); ?>