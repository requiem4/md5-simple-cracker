<?php
/**
 * @var $content string
 */
?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Backend Developer Test</title>
	<link rel="shortcut icon" href="https://admin.smarterclick.co.uk/images/favicon.ico">
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="/assets/css/animate.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/css/font-awesome.min.css">
<body>
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Brand</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
				<li><a href="#">Link</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#">Action</a></li>
						<li><a href="#">Another action</a></li>
						<li><a href="#">Something else here</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="#">Separated link</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="#">One more separated link</a></li>
					</ul>
				</li>
			</ul>
			<form class="navbar-form navbar-left">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Search">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#">Link</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#">Action</a></li>
						<li><a href="#">Another action</a></li>
						<li><a href="#">Something else here</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="#">Separated link</a></li>
					</ul>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
<main role="main">

	<!-- Main jumbotron for a primary marketing message or call to action -->
	<div class="jumbotron">
		<div class="container">
			<h2 class="display-3">Md5 Crash Checker</h2>
      <div style="display: flex">
        <form action="/" method="post">
          <input type="hidden" name="action" value="runDecode"/>
					<?php
					if(!isset($params['running_decode'])):
						?>
            <button type="submit" class="btn btn-primary"> Run Decode</button>
					<?php
          elseif (isset($params['running_decode'])):
						?>
            <button type="submit" class="btn btn-warning"> Stop Decode</button>
					<?php
					endif;
					?>
        </form>
        <form action="/" method="post">
          <input type="hidden" name="action" value="flushPasswords"/>
          <button type="submit" class="btn btn-danger"> Clear passwords</button>
        </form>
      </div>
		</div>
	</div>

	<div class="container">
		<!-- Example row of columns -->
		<div class="row">
			<?=$content?>
		</div>

		<hr>

	</div> <!-- /container -->

</main>
<script src="/assets/js/jquery-1.11.1.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
</body>
</html>
