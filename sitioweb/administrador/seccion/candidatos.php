<?php include("../template/cabecera.php"); ?>
<?php 

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtPartido=(isset($_POST['txtPartido']))?$_POST['txtPartido']:"";
$txtPropuesta=(isset($_POST['txtPropuesta']))?$_POST['txtPropuesta']:"";
$txtTrayectoria=(isset($_POST['txtTrayectoria']))?$_POST['txtTrayectoria']:"";
$txtEdad=(isset($_POST['txtEdad']))?$_POST['txtEdad']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include("../config/bd.php");

switch($accion){
        case "Agregar":
            
            
            $sentenciaSQL= $conexion->prepare("INSERT INTO candidatos (nombre, partido, propuesta, trayectoria, edad, imagen ) VALUES (:nombre, :partido, :propuesta, :trayectoria, :edad, :imagen);");
            $sentenciaSQL->bindParam(':nombre',$txtNombre);
            $sentenciaSQL->bindParam(':partido',$txtPartido);
            $sentenciaSQL->bindParam(':propuesta',$txtPropuesta);
            $sentenciaSQL->bindParam(':trayectoria',$txtTrayectoria);
            $sentenciaSQL->bindParam(':edad',$txtEdad);

            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            if($tmpImagen!=""){

                    move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
            }

            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->execute();

            header("Location:candidatos.php");
            break; 

        case "Modificar": 

            $sentenciaSQL= $conexion->prepare("UPDATE candidatos SET nombre=:nombre, partido=:partido, propuesta=:propuesta, trayectoria=:trayectoria, edad=:edad WHERE id=:id");
            $sentenciaSQL->bindParam(':nombre',$txtNombre);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->bindParam(':partido',$txtPartido);
            $sentenciaSQL->bindParam(':propuesta',$txtPropuesta);
            $sentenciaSQL->bindParam(':trayectoria',$txtTrayectoria);
            $sentenciaSQL->bindParam(':edad',$txtEdad);
            $sentenciaSQL->execute();
            
            if($txtImagen!=""){

                $fecha= new DateTime();
                $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
                $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

                move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

                $sentenciaSQL= $conexion->prepare("SELECT imagen FROM candidatos WHERE id=:id");
                $sentenciaSQL->bindParam(':id',$txtID);
                $sentenciaSQL->execute();
                $candidato=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
                
                if( isset($imgCandidato["imagen"]) &&($imgCandidato["imagen"]!="imagen.jpg") ){
    
                    if(file_exists("../../img/".$imgCandidato["imagen"])){
    
                        unlink("../../img/".$imgCandidato["imagen"]);
                    }
    
                }

                

                $sentenciaSQL= $conexion->prepare("UPDATE candidatos SET imagen=:imagen WHERE id=:id");
                $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
                $sentenciaSQL->bindParam(':id',$txtID);
                $sentenciaSQL->execute();
            }
            header("Location:candidatos.php");
            break; 

        case "Cancelar": 
             header("Location:candidatos.php");
            break; 

        case "Seleccionar": 
           
            $sentenciaSQL= $conexion->prepare("SELECT * FROM candidatos WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $candidato=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
            
            $txtNombre=$candidato['nombre'];
            $txtImagen=$candidato['imagen'];
            $txtPartido=$candidato['partido'];
            $txtPropuesta=$candidato['propuesta'];
            $txtTrayectoria=$candidato['trayectoria'];
            $txtEdad=$candidato['edad'];

                // echo "Presionado botón Seleccionar";
               break;

        case "Borrar": 

            $sentenciaSQL= $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $candidato=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
            
            if( isset($candidato["imagen"]) &&($candidato["imagen"]!="imagen.jpg") ){

                if(file_exists("../../img/".$candidato["imagen"])){

                    unlink("../../img/".$candidato["imagen"]);
                }

            }



               $sentenciaSQL= $conexion->prepare("DELETE FROM candidatos WHERE id=:id");
                $sentenciaSQL->bindParam(':id',$txtID);
                $sentenciaSQL->execute();
                header("Location:candidatos.php");
               break;
}

$sentenciaSQL= $conexion->prepare("SELECT * FROM candidatos");
$sentenciaSQL->execute();
$listaCandidatos=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);


?>


<div class="col-md-5">
    
    <div class="card">
        <div class="card-header">
            Datos del Candidato
        </div>

        <div class="card-body">
           
        <form method="POST" enctype="multipart/form-data" >

    <div class = "form-group">
    <label for="txtID">ID:</label>
    <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">
    </div>

    <div class = "form-group">
    <label for="txtNombre">Nombre:</label>
    <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre del candidato">
    </div>

    <div class = "form-group">
    <label for="txtEdad">Edad:</label>
    <input type="text" required class="form-control" value="<?php echo $txtEdad; ?>" name="txtEdad" id="txtEdad" placeholder="Edad">
    </div>

    <div class = "form-group">
    <label for="txtPartido">Partido:</label>
    <input type="text" required class="form-control" value="<?php echo $txtPartido; ?>" name="txtPartido" id="txtPartido" placeholder="Partido">
    </div>

    <div class = "form-group">
    <label for="txtTrayectoria">Trayectoria:</label>
    <input type="text" required class="form-control" value="<?php echo $txtTrayectoria; ?>" name="txtTrayectoria" id="txtTrayectoria" placeholder="Trayectoria">
    </div>

    <div class = "form-group">
    <label for="txtPropuesta">Propuesta:</label>
    <textarea maxlength="255" required class="form-control" name="txtPropuesta" id="txtPropuesta" placeholder="Propuesta"><?php echo $txtPropuesta; ?></textarea>
    </div>

    <div class = "form-group">
    <label for="txtNombre">Imagen:</label>

   <br/>

    <?php   if($txtImagen!=""){  ?>
        
        <img class="img-thumbnail rounded"  src="../../img/<?php echo $txtImagen;?>" width="50" alt="" srcset="">

                

    <?php   } ?>

    <input type="file" class="form-control"  name="txtImagen" id="txtImagen" placeholder="Nombre del libro">
    </div>


        <div class="btn-group" role="group" aria-label="">
            <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":""; ?> value="Agregar" class="btn btn-success">Agregar</button>
            <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":""; ?> value="Modificar"class="btn btn-warning">Modificar</button>
            <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":""; ?>  value="Cancelar" class="btn btn-info">Cancelar</button>
        </div>


    </form>

        </div>

       
    </div>


    
    
    

</div>
<div class="col-md-7">
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Edad</th>
                <th>Partido</th>
                <th>Trayectoria</th>
                <th>Propuesta</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php  foreach($listaCandidatos as $candidato) { ?>
            <tr>
                <td><?php echo $candidato['id']; ?></td>
                <td><?php echo $candidato['nombre']; ?></td>
                <td><?php echo $candidato['edad']; ?></td>
                <td><?php echo $candidato['partido']; ?></td>
                <td>...</td>
                <td>...</td>
                <td>
                
                <img class="img-thumbnail rounded" src="../../img/<?php echo $candidato['imagen']; ?>" width="50" alt="" srcset="">

                
                
                </td>

                <td>
                <form method="post">

                    <input type="hidden" name="txtID" id="txtID" value="<?php echo $candidato['id']; ?>" />

                    <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>

                    <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>
                
                
                </form>

                
                </td>

            </tr>
           <?php } ?>
        </tbody>
    </table>


</div>



<?php include("../template/pie.php"); ?>