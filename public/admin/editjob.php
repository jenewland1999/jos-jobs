<?php
$pdo = new PDO('mysql:dbname=job;host=127.0.0.1', 'student', 'student', [PDO::ATTR_ERRMODE =>  PDO::ERRMODE_EXCEPTION ]);
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="/public/styles/main.css"/>
		<title>Jo's Jobs - Edit Job</title>
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
	<img src="//public/images/random-banner.php"/>
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

		$stmt = $pdo->prepare('UPDATE job
								SET title = :title,
								    description = :description,
								    salary = :salary,
								    location = :location,
								    categoryId = :categoryId,
								    closingDate = :closingDate
								   WHERE id = :id
						');

		$criteria = [
			'title' => $_POST['title'],
			'description' => $_POST['description'],
			'salary' => $_POST['salary'],
			'location' => $_POST['location'],
			'categoryId' => $_POST['categoryId'],
			'closingDate' => $_POST['closingDate'],
			'id' => $_POST['id']
		];

		$stmt->execute($criteria);


		echo 'Job saved';
	}
	else {
		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

			$stmt = $pdo->prepare('SELECT * FROM job WHERE id = :id');

			$stmt->execute($_GET);

			$job = $stmt->fetch();
		?>

			<h2>Edit Job</h2>

			<form action="editjob.php" method="POST">

				<input type="hidden" name="id" value="<?php echo $job['id']; ?>" />
				<label>Title</label>
				<input type="text" name="title" value="<?php echo $job['title']; ?>" />

				<label>Description</label>
				<textarea name="description"><?php echo $job['description']; ?></textarea>

				<label>Location</label>
				<input type="text" name="location" value="<?php echo $job['location']; ?>" />


				<label>Salary</label>
				<input type="text" name="salary" value="<?php echo $job['salary']; ?>" />

				<label>Category</label>

				<select name="categoryId">
				<?php
					$stmt = $pdo->prepare('SELECT * FROM category');
					$stmt->execute();

					foreach ($stmt as $row) {
						if ($job['categoryId'] == $row['id']) {
							echo '<option selected="selected" value="' . $row['id'] . '">' . $row['name'] . '</option>';
						}
						else {
							echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
						}

					}

				?>

				</select>

				<label>Closing Date</label>
				<input type="date" name="closingDate" value="<?php echo $job['closingDate']; ?>"  />

				<input type="submit" name="submit" value="Save" />

			</form>

		<?php
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

	}
	?>

</section>
	</main>
	<footer>
		&copy; Jo's Jobs 2017
	</footer>
</body>
</html>
