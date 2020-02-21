<?php
$pdo = new PDO('mysql:dbname=job;host=127.0.0.1', 'student', 'student', [PDO::ATTR_ERRMODE =>  PDO::ERRMODE_EXCEPTION ]);
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="/styles.css"/>
		<title>Jo's Jobs - Apply</title>
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

		if (isset($_POST['submit'])) {





		if ($_FILES['cv']['error'] == 0) {

			$parts = explode('.', $_FILES['cv']['name']);

			$extension = end($parts);

			$fileName = uniqid() . '.' . $extension;

			move_uploaded_file($_FILES['cv']['tmp_name'], 'cvs/' . $fileName);

			$criteria = [
				'name' => $_POST['name'],
				'email' => $_POST['email'],
				'details' => $_POST['details'],
				'jobId' => $_POST['jobId'],
				'cv' => $fileName
			];

			$stmt = $pdo->prepare('INSERT INTO applicants (name, email, details, jobId, cv)
							   VALUES (:name, :email, :details, :jobId, :cv)');

			$stmt->execute($criteria);

			echo 'Your application is complete. We will contact you after the closing date.';


		}
		else {
			echo 'There was an error uploading your CV';
		}



	}
	else {

			$stmt = $pdo->prepare('SELECT * FROM job WHERE id = :id');

			$stmt->execute($_GET);

			$job = $stmt->fetch();
			?>

			<h2>Apply for <?=$job['title'];?></h2>

			<form action="apply.php" method="POST" enctype="multipart/form-data">
				<label>Your name</label>
				<input type="text" name="name" />

				<label>E-mail address</label>
				<input type="text" name="email" />

				<label>Cover letter</label>
				<textarea name="details"></textarea>

				<label>CV</label>
				<input type="file" name="cv" />

				<input type="hidden" name="jobId" value="<?=$job['id'];?>" />

				<input type="submit" name="submit" value="Apply" />

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


