<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo Kohana::$charset; ?>" />
	<title><?php echo $title; ?></title>
    <?php foreach ($styles as $file => $type) echo HTML::style($file, array('media' => $type)), "\n" ?>
    <?php foreach ($scripts as $file) echo HTML::script($file), "\n" ?>
</head>
<body>

<h1><?php echo $title; ?></h1>

<?php echo $content; ?>

</body>
</html>