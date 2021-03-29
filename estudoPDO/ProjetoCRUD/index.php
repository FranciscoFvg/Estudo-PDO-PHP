<?php

    require_once 'class/classe-pessoa.php';
    $p = new Pessoa('CRUDPDO', "localhost", "root","");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Pessoa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <section id="esquerda">
        <form action="" method="POST">

            <h2>Cadastrar Pessoa</h2>

            <label for="nome">Nome: </label>
            <input type="text" name="nome" id="nome">

            <label for="telefone">Telefone: </label>
            <input type="text" name="telefone" id="telefone">

            <label for="email">Email: </label>
            <input type="email" name="email" id="email">

            <input type="submit" value="Cadastrar">
        
        </form>
        <?php
            //verifica se o botao de cadastrar foi clicado e dados preenchidos
            if (isset($_POST['nome'])) {
                //addslashes() impede que códigos maliciosos sejam enviados ao site
                $nome = addslashes($_POST['nome']);
                $telefone = addslashes($_POST['telefone']);
                $email = addslashes($_POST['email']);

                if (!empty($nome) && !empty($telefone) && !empty($email)) {
                    if (!$p->cadastrarPessoa($nome,$telefone,$email)) {
                        echo '<h3>Email já cadastrado!</h3>';
                    }
                    
                }else{
                    echo "<h3>Preencha todos os campos especificados!</h3>";
                }
            }
        ?>
    </section>

    <section id="direita">
        <table>
            <tr id="titulo">
                <td>NOME</td>
                <td>TELEFONE</td>
                <td colspan="2">EMAIL</td>
            </tr>
            <?php

            $dados = $p->buscarDados();

            if (count($dados) > 0) {//TEM PESSOAS NO BANCO?
                for ($i=0; $i < count($dados); $i++) { 
                    echo "<tr>";
                    foreach ($dados[$i] as $k => $v) {
                        if ($k != "id") {
                            
                            echo "<td>".$v."</td>";

                        }
                    }
                    echo '<td>
                        <a href="">Editar</a>
                        <a href="">Deletar</a>
                    </td>';
                    echo "</tr>";
                }
            }else{//BANCO VAZIO
                echo "Não existem pessoas cadastradas no banco";
            }
            ?>

        </table>
    </section>
</body>
</html>