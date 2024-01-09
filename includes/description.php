<?php

/*if(isset($_GET['del_id'])){

  if(isset($_SESSION['admin']) && ($_SESSION['admin']==1)){
      mysqli_query($conx,"UPDATE produktuak SET descripzioa = '' WHERE ID LIKE ".$_GET['del_id']);
    header("Location: ".$_SERVER['PHP_SELF']);
  }else{
    header("Location: ".$_SERVER['PHP_SELF']);
  }

}*/

if(isset($_GET['postdescription'])){

    $stmt = $conx->prepare("SELECT deskripzioa, salneurria FROM produktuak WHERE ID LIKE ?");
    $stmt->bind_param("i", $_GET['pic_id']);
    $stmt->execute();
    $stmt->bind_result($deskripzioa, $salneurria);
    $stmt->fetch();
    $stmt->close();

    $deskripzioa = htmlspecialchars($_POST['deskripzioa'], ENT_QUOTES, 'UTF-8');

    if ($_POST['salneurria'] != '') {
        $salneurria = htmlspecialchars($_POST['salneurria'], ENT_QUOTES, 'UTF-8');
    }

    $stmt = $conx->prepare("UPDATE produktuak SET deskripzioa = ?, salneurria = ? WHERE ID LIKE ?");
    $stmt->bind_param("sdi", $deskripzioa, $salneurria, $_GET['pic_id']);
    $stmt->execute();
    $stmt->close();

    

    header("Location: ".$_SERVER['PHP_SELF']);
  
}else{

?>

<div align=center>
    <fieldset style=width:300;>
    <legend><b>Descripción</b></legend>
    <br>
    <?php

        $stmt = $conx->prepare("SELECT * FROM produktuak WHERE ID =?");
        $stmt->bind_param("i", $_GET['pic_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        echo "<h4>" . htmlentities($data['izena']) . " - " . htmlentities($data['salneurria']) . "€</h4>";
        echo "<img src=images/" . htmlentities($data['pic']) . " border=1><br>";

    ?>
    <br>
    <form action="<?php echo $_SERVER['PHP_SELF']."?action=description&pic_id=".$_GET['pic_id']."&postdescription=1";?>" method=POST>
        <h5>Deskripzioa:</h5>
        <textarea name=deskripzioa cols=50 rows=10>
            <?php
            echo $data['deskripzioa'];
            ?>
        </textarea><br>
        <br>
        Salneurri berria: <input type="text" name="salneurria">
        <br>
        <br>
        <input type=submit value="Aldatu">
    </form>
    </fieldset>
</div>

<?php

}

?>