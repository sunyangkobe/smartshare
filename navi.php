<ul>

	<li>
		<a href='index.php?action=home' title='Home' class='current'>
			<span>HOME</span>
		</a>
	</li>

	<li>
		<a href='index.php?action=search' title='Find Trip'>
			<span>FIND TRIP</span>
		</a>
	</li>
	
	<li>
		<a href='index.php?action=list_trip' title='List Trip'>
			<span>LIST TRIP</span>
		</a>
	</li>

	<li>
		<a href='index.php?action=info' title='Information'>
			<span>INFORMATION</span>
		</a>
	</li>

	<?php if (retrieveUser()) { ?>
	
	<li>
		<a href='index.php?action=profile' title='Profile'>
			<span>PROFILE</span>
		</a>
	</li>
	
	<li>
		<a href='index.php?action=create_trip' title='Create Trip'>
			<span>CREATE TRIP</span>
		</a>
	</li>
	
	<li>
		<a href='index.php?action=personal_trip' title='My Trip'>
			<span>MY TRIP</span>
		</a>
	</li>
	
	<li>
		<a href='index.php?action=friends' title='Friends'>
			<span>FRIENDS</span>
		</a>
	</li>
	
	<?php } else { ?>
	
	<li>
		<a href='index.php?action=register' title='Register'>
			<span>REGISTER</span>
		</a>
	</li>
		<?php } ?>

	<?php 	$user = retrieveUser();
	if ($user && $user->isAdmin()) { ?>
	
	<li>
		<a href='index.php?action=adminview' title='Admin View'>
			<span>ADMIN VIEW</span>
		</a>
	</li>
	<?php } ?>
	

</ul>

