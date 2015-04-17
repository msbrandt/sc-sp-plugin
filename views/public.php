<?php 
function display_mixer(){
?>
<section>
	<div class="deck" id="deck-a" data-volume_control="vo-a">
		<audio class="audio" id="au-a"></audio>

		<div class="active-deck-button"><div class="glyphicon"></div></div>
		<div class="vinyl">
				<div class="time-display">
					<span class="current-time">0:00</span>
					<span>/</span>
					<span class="total-time">0:00</span>
				</div>
				<div class="vinyl-layer0">
					<div class="vinyl-layer1">
						<div class="vinyl-layer2">

						</div>
					</div>
				</div>
		</div>

		<div class="progress">
			<input class="wave-prog" value="0" id="wave-a" type="range" min='0' max="">			
		</div>
		<div class="display">
			<div class="now-playing">none</div>
		</div>
		<div class="toggle-button sc-stop" id="tog-a">
			<div class="glyphicon glyphicon-play"></div>	
		</div>
	</div>

	<div class="deck" id="deck-b" data-volume_control="vo-b">
		<audio class="audio" id="au-b"></audio>

		<div class="active-deck-button"><div class="glyphicon"></div></div>

			<div class="vinyl">
				<div class="time-display">
					<span class="current-time">0:00</span>
					<span>/</span>
					<span class="total-time">0:00</span>
				</div>
				<div class="vinyl-layer0">
					<div class="vinyl-layer1">
						<div class="vinyl-layer2">

						</div>
					</div>
				</div>
			</div>

		<div class="progress">

			<input class="wave-prog" value="0" id="wave-b" type="range" min='0' max="">

		</div>
		<div class="display">
			<div class="now-playing">none</div>
		</div>
		<div class="toggle-button sc-stop" id="tog-b">
			<div class="glyphicon glyphicon-play"></div>	
		</div>
	</div>
	<div id="volum">
		<div class='vol-container'>
			<div class="v-slider" id="v-a">
				<input type="range" min="0" max="1" value="0" step="0.05" class="vo-slider" id='vo-a'>
			</div>
			<div class="v-slider" id="v-b">
				<input type="range" min="0" max="1" value="0" step="0.05" class="vo-slider" id='vo-b'>		
			</div>
		</div>
	</div>
	<div id="slider">
		<input type="range" min="-1" max="1" value="0" step=".05" id="sli">
	</div>
<div id="open-playlist"><span class="glyphicon glyphicon-open"></span></div>

<div id="loaded-playlist" data-isopen="false">
	<ul>
	<?php
		mixer_showcase::load_song_list_li();
	?>
	</ul>
</div>
</section>


<?php

	}
//add this to post to display plugin 
add_shortcode( 'SoundCloud_mixer', 'display_mixer' ); 
?>