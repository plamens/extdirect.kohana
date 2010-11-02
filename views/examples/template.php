<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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