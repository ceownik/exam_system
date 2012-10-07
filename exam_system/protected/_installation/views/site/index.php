<div class="main">
	<?php if((!$configWritable) || (!$assetsWritable) || (!$runtimeWritable) ) : ?>
	<div class="error">
		<p>Instalacja nie może być przeprowadzona, niektóre pliki lub katalogi wymagają praw do zapisu:</p>
		
		<ul>
			<?php if(!$configWritable): ?><li>/protected/config/db-config.php</li><?php endif; ?>
			<?php if(!$assetsWritable): ?><li>/assets</li><?php endif; ?>
			<?php if(!$runtimeWritable): ?><li>/protected/runtime</li><?php endif; ?>
		</ul>
	</div>
	<?php else : ?>
		<?php if($step == 1) : ?>
			<div class="installation">
				<h4 class="step-title">loading...</h4>
				<div class="installation-content">
					
				</div>
			
			
				<div style="display: none;">
					<span class="base-url"><?php echo Yii::app()->createAbsoluteUrl(''); ?></span>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>



