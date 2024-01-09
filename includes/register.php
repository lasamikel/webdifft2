<?php
// assign defaults

// $email="";
// $izena="";
// $abizena="";
// $hiria="";
// $lurraldea="";
// $herrialdea="";
// $postakodea="";
// $telefono="";
// $pasahitza="";
// $pasahitza_errepikatu="";


$data = array('email' 		=> '',
			  'firstname' 	=> '',
			  'lastname' 	=> '',
			  'postcode' 	=> '',
			  'city' 		=> '',
			  'stateProv' 	=> '',
			  'country'		=> '',
			  'telephone' 	=> '',
			  'password' 	=> '',
			  'password2' 	=> '',
			  'imagen'      => ''
);

$error = array('email' 	  => '',
			  'firstname' => '',
			  'lastname'  => '',
			  'city'	  => '',
			  'stateProv' => '',
			  'country'	  => '',
			  'postcode'  => '',
			  'telephone' => '',
			  'password'  => '',
);

if (isset($_POST['data']["submit"])) {
	$data = $_POST['data'];

    $path = "perfiles/".basename($_FILES['imagen']['name']);
    move_uploaded_file($_FILES['imagen']['tmp_name'], $path);
    $data['imagen'] = basename($_FILES['imagen']['name']);

	
	$data["email"] = htmlspecialchars($data["email"], ENT_QUOTES, 'UTF-8');
    if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)){
        $error["email"] = "Error en el campo email";
    } 

	$data["firstname"] = htmlspecialchars($data["firstname"], ENT_QUOTES, 'UTF-8'); 
    if (!preg_match('/^[a-zA-Z]+$/',$data["firstname"])){
        $error["firstname"] = "Error en el campo izena";
    } 

	$data["lastname"] = htmlspecialchars($data["lastname"], ENT_QUOTES, 'UTF-8');
    if (!preg_match('/^[a-zA-Z]+$/', $data["lastname"])){
        $error["lastname"] = "Error en el campo abizena";
    } 

	$data["city"] = htmlspecialchars($data["city"], ENT_QUOTES, 'UTF-8');
    if (!preg_match('/^[a-zA-Z ]+$/', $data["city"])){
        $error["city"] = "Error en el campo hiria";
    } 

	$data["stateProv"] = htmlspecialchars($data["stateProv"], ENT_QUOTES, 'UTF-8');
    if (!preg_match('/^[a-zA-Z ]+$/', $data["stateProv"])){
        $error["stateProv"] = "Error en el campo lurraldea";
    } 

	$data["country"] = htmlspecialchars($data["country"], ENT_QUOTES, 'UTF-8'); 
    if (!preg_match('/^[a-zA-Z ]+$/', $data["country"])){
        $error["country"] = "Error en el campo herrialdea";
    } 

	$data["postcode"] = htmlspecialchars($data["postcode"], ENT_QUOTES, 'UTF-8'); 
    if (!filter_var($data["postcode"], FILTER_VALIDATE_INT)){
		$error["postcode"] = "Error en el campo postkodea";
    } 

	$data["telephone"] = htmlspecialchars($data["telephone"], ENT_QUOTES, 'UTF-8'); 
    if (!filter_var($data["telephone"], FILTER_VALIDATE_INT)){
        $error["telephone"] = "Error en el campo telefonoa";
    } 

	// $pass=md5($data['password']);
	
	
	if($data['password'] != $data['password2'] and strlen($data['password']) > 5){
		$error["password"] = "Error en el campo pasahitza";
	}

	$pass = password_hash(htmlspecialchars($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT);

	if(!$error["email"] and !$error["firstname"] and !$error["lastname"] and !$error["city"] and !$error["stateProv"] and !$error["country"] and !$error["postcode"] and !$error["telephone"] and !$error["password"]){
		$stmt = $conx->prepare("INSERT INTO users(username, password, izena, abizena, hiria, lurraldea, herrialdea, postakodea, telefonoa, irudia) VALUES (?,?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param("sssssssiis", $data['email'],  $pass, $data['firstname'], $data['lastname'], $data['city'], $data['stateProv'], $data['country'], $data['postcode'], $data['telephone'], $data['imagen']);
		$stmt->execute();
		$stmt->close();
	
		if ($conx->errno) {
			die('Error: ' . $conx->error);
		} else {
			header("Location: index.php");
		}
	}
		

}

?>
	<div class="content">
	<br/>
	<div class="register">

		<h2>Erregistroa egin</h2>
		<br/>

		<b>Introduce la información.</b>
		<br/>
		<form action="<?php echo $_SERVER['PHP_SELF']."?action=register"; ?>" method="POST" enctype="multipart/form-data">
			<p>
				<label>Email/username: </label>
				<input type="text" name="data[email]" value="<?php echo $data['email']; ?>" />
				<?php if ($error['email']) echo '<p>', $error['email']; ?>
			<p>
				
			<p>
				<label>Izena: </label>
				<input type="text" name="data[firstname]" value="<?php echo $data['firstname']; ?>" />
				<?php if ($error['firstname']) echo '<p>', $error['firstname']; ?>
			<p>
			<p>
				<label>Abizena: </label>
				<input type="text" name="data[lastname]" value="<?php echo $data['lastname']; ?>" />
				<?php if ($error['lastname']) echo '<p>', $error['lastname']; ?>
			<p>
			<p>
				<label>Hiria: </label>
				<input type="text" name="data[city]" value="<?php echo $data['city']; ?>" />
				<?php if ($error['city']) echo '<p>', $error['city']; ?>
			<p>
			<p>
				<label>Lurraldea: </label>
				<input type="text" name="data[stateProv]" value="<?php echo $data['stateProv']; ?>" />
				<?php if ($error['stateProv']) echo '<p>', $error['stateProv']; ?>
			<p>
			<!-- // *** validation: implement a database lookup -->
			<p>
				<label>Herrialdea: </label>
				<input type="text" name="data[country]" value="<?php echo $data['country']; ?>" />
				<?php if ($error['country']) echo '<p>', $error['country']; ?>
			<p>
			<p>
				<label>Postakodea: </label>
				<input type="text" name="data[postcode]" value="<?php echo $data['postcode']; ?>" />
				<?php if ($error['postcode']) echo '<p>', $error['postcode']; ?>
			<p>
			<p>
				<label>Telefonoa: </label>
				<input type="text" name="data[telephone]" value="<?php echo $data['telephone']; ?>" />
				<?php if ($error['telephone']) echo '<p>', $error['telephone']; ?>
			<p>
			<p>
				<label>Pasahitza: </label>
				<input type="password" name="data[password]" value="<?php echo $data['password']; ?>" />
				<?php if ($error['password']) echo '<p>', $error['password']; ?>
			<p>
            <p>
                <label>Pasahitza errepikatu: </label>
                <input type="password" name="data[password2]" value="<?php echo $data['password2']; ?>" />
            <p>
            <p>
                <label>Irudia aukeratu:</label>
                <input name="imagen" type="file" />
            <p>
			<p>
				<input type="reset" name="data[clear]" value="Clear" class="button"/>
				<input type="submit" name="data[submit]" value="Submit" class="button marL10"/>
			<p>
		</form>
	</div>
</div>
