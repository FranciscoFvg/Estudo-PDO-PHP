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
    <?php

        if (isset($_GET['id_update'])) {
            $id_update = addslashes($_GET['id_update']);
            $res = $p->buscarDadosPessoa($id_update);
        }

    ?>
    <section id="esquerda">
        <form action="" method="POST">

            <h2>Cadastrar Pessoa</h2>

            <label for="nome">Nome: </label>
            <input type="text" name="nome" id="nome" value="<?php if (isset($res)) { echo $res['nome'];} ?>">

            <label for="telefone">Telefone: </label>
            <input type="text" name="telefone" id="telefone" value="<?php if (isset($res)) { echo $res['telefone'];} ?>">

            <label for="email">Email: </label>
            <input type="email" name="email" id="email" value="<?php if (isset($res)) { echo $res['email'];}?>">

            <input type="submit" value="<?php if (isset($res)) { echo "Atualizar";}else{echo "Cadastrar";} ?>">
        
        </form>
        <?php
            //verifica se o botao de cadastrar foi clicado e dados preenchidos
            if (isset($_POST['nome'])) {//Clicou no botão cadastrar ou atualizar


                if (isset($_GET['id_update']) && !empty('id_update')) {//EDITAR
                    
                    //addslashes() impede que códigos maliciosos sejam enviados ao site
                    $nome = addslashes($_POST['nome']);
                    $telefone = addslashes($_POST['telefone']);
                    $new_email = addslashes($_POST['email']);
                    $id_update = addslashes($_GET['id_update']);
                    $old_email  = $res['email'];

                    if (!empty($nome) && !empty($telefone) && !empty($new_email)) {
                        if ($p->atualizarDados($id_update,$nome,$telefone,$old_email, $new_email)) {
                            header("location: index.php");
                        }else{
                            echo '<h3>Email já cadastrado!</h3>';
                        }
                        
                    }else{
                        echo "<h3>Preencha todos os campos especificados!</h3>";
                    }

                }else{//CADASTRAR

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
            ?>
            
                <td>
                    <a href="index.php?id_update=<?php echo $dados[$i]['id']; ?>">Editar</a>
                    <a href="index.php?id=<?php echo $dados[$i]['id']; ?>">Deletar</a>
                </td>
            </tr>

        </table>
        <?php
                }
            }else{//BANCO VAZIO
                echo "Não existem pessoas cadastradas no banco";
            }
            ?>
    </section>
</body>
</html>

<?php

    if (isset($_GET['id'])) {
        $id_pessoa = addslashes($_GET['id']);
        $p->excluirPessoa($id_pessoa);
        //header() usado para atualizar a página após a operação
        header("location: index.php");
    }

?>