

<span style="text-align:center;width:300px;margin:auto;display:block;margin-top:20px;">Actualizar {Titulo}</span>

<div style="height: 400px">
<div id="contenedor">
    <div id="formulario" style="display:none;">
    </div>
    <div id="tabla">

        <link rel="stylesheet" type="text/css" href="../skins/saam/plantillas/mantenedores.css" media="screen" />                   
        
        <form id="frmOrgActualizar" name="frmOrgActualizar" method="post" action="manActualizarOrganizaciones.php">
            <input type="hidden" name="organizacionId" id="organizacionId" value="<?php echo $organizacion['organizacionId'] ?>"/>
            <p>
                <label>Nombre<br />
                    <input class="text" type="text" name="organizacionNombre" id="organizacionNombre" value= "<?php echo $organizacion['organizacionNombre'] ?>" />
                </label>
            </p>
            <p>
                <label>Logo<br />
                    <input class="text" type="text" name="organizacionLogo" id="organizacionLogo" value= "<?php echo $organizacion['organizacionLogo'] ?>" />
                </label>
            </p>
            <p>
                <label>Descripción<br />
                    <input class="text" type="text" name="organizacionDescripcion" id="organizacionDescripcion" value= "<?php echo $organizacion['organizacionDescripcion'] ?>" />
                </label>
            </p>
            <p>
                <label>
                    <input type="radio" name="organizacionEstado" id="estado_1" value="1" />
                    Activo</label>
                <label>
                    <input type="radio" name="organizacionEstado" id="estado_0" value="0" />
                    Inactivo</label>
            </p>
                        
            <p>
                <br>
                <input type="submit" name="submit" id="button" value="Actualizar" />
                <label></label>
                <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="Cancelar()" />
            </p>
        </form>        
        
    </div>
</div>  
        </div>


