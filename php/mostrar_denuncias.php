<?php

    include "conexao.php";

    $resultado = mysqli_query($conexao,"SELECT * FROM denuncias");

    $i = 0;

    while($registro = mysqli_fetch_assoc($resultado)){

        $dados[$i]["data_denuncia"] = $registro["data_denuncia"];
        $dados[$i]["loca_denuncia"] = $registro["local_denuncia"];
        $dados[$i]["desc_denuncia"] = $registro["desc_denuncia"];
        $dados[$i]["num_animais"] = $registro["num_animais"];
        $dados[$i]["especie"] = $registro["especie"];
        $i++;

    }



    $objetoJSON = json_encode($dados);

    echo $objetoJSON;

?>