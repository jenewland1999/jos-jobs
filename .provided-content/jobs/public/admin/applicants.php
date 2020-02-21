<?php
$pdo = new PDO('mysql:dbname=job;host=127.0.0.1', 'student', 'student', [PDO::ATTR_ERRMODE =>  PDO::ERRMODE_EXCEPTION ]);
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="/styles.css"/>
		<title>Jo's Jobs - Applicants</title>
	</head>
	<body>
	<header>
		<section>
			<aside>
				<h3>Office Hours:</h3>
				<p>Mon-Fri: 09:00-17:30</p>
				<p>Sat: 09:00-17:00</p>
				<p>Sun: Closed</p>
			</aside>
			<h1>Jo's Jobs</h1>

		</section>
	</header>
	<nav>
		<ul>
			<li><a href="/">Home</a></li>
			<li>Jobs
				<ul>
					<li><a href="/it.php">IT</a></li>
					<li><a href="/hr.php">Human Resources</a></li>
					<li><a href="/sales.php">Sales</a></li>
				</ul>
			</li>
			<li><a href="/about.html">About Us</a></li>
		</ul>

	</nav>
	<img src="/images/randombanner.php"/>
	<main class="sidebar">

	<section class="left">
		<ul>
			<li><a href="jobs.php">Jobs</a></li>
			<li><a href="categories.php">Categories</a></li>

		</ul>
	</section>

	<section class="right">

	<?php

		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

			$stmt = $pdo->prepare('SELECT * FROM job WHERE id = :id');

			$stmt->execute(['id' => $_GET['id']]);

			$job = $stmt->fetch();
		?>


			<h2>Applicants for <?=$job['title'];?></h2>

			<?php
			echo '<table>';
			echo '<thead>';
			echo '<tr>';
			echo '<th style="width: 10%">Name</th>';
			echo '<th style="width: 10%">Email</th>';
			echo '<th style="width: 65%">Details</th>';
			echo '<th style="width: 15%">CV</th>';
			echo '</tr>';

			$stmt = $pdo->prepare('SELECT * FROM applicants WHERE jobId = :id');

			$stmt->execute(['id' => $_GET['id']]);

			foreach ($stmt as $applicant) {
				echo '<tr>';
				echo '<td>' . $applicant['name'] . '</td>';
				echo '<td>' . $applicant['email'] . '</td>';
				echo '<td>' . $applicant['details'] . '</td>';
				echo '<td><a href="/cvs/' . $applicant['cv'] . '">Download CV</a></td>';
				echo '</tr>';
			}

			echo '</thead>';
			echo '</table>';

		}

		else {
			?>
			<h2>Log in</h2>

			<form action="index.php" method="post">
				<label>Password</label>
				<input type="password" name="password" />

				<input type="submit" name="submit" value="Log In" />
			</form>
		<?php
		}
	?>

</section>
	</main>

	<footer>
		&copy; Jo's Jobs 2017
	</footer>
</body>
</html>


