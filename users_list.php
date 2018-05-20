<?php 
$banner_text = 'Users';
$banner_image = './layout/random.jpg';
include './layout/layout-top2.php';
?> 
<style>
@media (max-width: 601px) {
	.search {
		width: 250px;
	}
}
</style>
<!-- content goes here -->
<input type="text" class="search box"></input> <img src="./icons/search.png" class="search_image">
<div class='user_list_container'>
<ul class='users_list_ul'>
<?php 
$users = new Users();
$users->listAll()  ?>
</ul>
</div>

			</div>
			<div id="related">
				admins:
			</div>
<script src='./ajax/jquery/user_search.js' type='text/javascript'></script>

<?php include './layout/layout-bottom.php'; ?>
