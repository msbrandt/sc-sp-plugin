<?php
/**
 * Roster Plugin
 *
 * @package   SoundCloud Mixer
 * @author    Mikey Brandt 
 */	

?>

<h1>Load your playlist here</h1>
<h3 id="status"></h3>
<form id="sc-load">
	<input class="sc-admin-input" id="load-list" type="file" placeholder="Select Playlist"></input>
	<div class="input-group">
		<input class="sc-admin-input" id="search-sc" type="input" placeholder="Search SoundCloud">

		<div id="sc-search-btn"><span class="dashicons dashicons-search"></span></div>
	</div>
	<div class="clear"></div>
	<div id="sc-load-btn" class="org-btn">Load</div>


</form>
<div id="select-to-load">
	<div class="sel-box" id="select-from">
		<h3>Please select the songs you would like to load</h3>
		<img src="<?php echo plugins_url( '../img/pageloaderat.gif', __FILE__ ); ?>"> 
		<ul class="loaded-tracks" id="select-from-box">

		</ul>
	</div>
	<div id="select-buttons">
		<div id="add-btn" class="sel-btn org-btn">Add</div>
		<div id="remove-btn" class="sel-btn org-btn">Delete</div>
		<div id="save-btn" class="sel-btn org-btn">Save</div>

	</div>
	<!-- <div id="select-btn">Select</div> -->

	<div class="sel-box" id='que-to-load'>
		<h3>Songs Loaded</h3>
		<ul class="loaded-tracks" id="already-loaded">
			<?php $this->load_song_list_tbl(); ?> 
		</ul>
	</div>
</div>
<div id="text-area"></div>
