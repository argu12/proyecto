<?php include("template/cabecera.php"); ?>
<?php 
include ("administrador/config/bd.php");
$sentenciaSQL= $conexion->prepare("SELECT * FROM candidatos");
$sentenciaSQL->execute();
$listaCandidatos=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container">
<br/>
<div class="row">

<h1>Candidatos</h1>
<?php foreach($listaCandidatos as $candidato ) { ?>

<div class="col-md-3">
<div class="card">
<img class="card-img-top" src="./img/<?php echo $candidato['imagen']; ?>" alt="">
<div class="card-body">
    <h4 class="card-title"><?php echo $candidato['nombre']; ?></h4>
    <a name="" id="" class="btn btn-primary" href="https://goalkicker.com/" role="button"> Ver más  </a>
</div>
</div>
</div>

<?php } ?>
</div>
</div>





<?php include("template/pie.php"); ?>