<?php

Class Pessoa{
    private $pdo;

    //6 funções
    //Conexão com banco de dados
    function __construct($dbname, $host, $user, $senha){

        try {
            $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host , $user, $senha);
        }catch(PDOException $e){
            echo "Erro de banco de dados: " . $e->getMessage();
            exit();
        } 
        catch (\Throwable $e) {
            echo "Erro genérico: " . $e->getMessage();
            exit();
        }
        
    }

    //Função para buscar os dados e colocar no canto direito da tela(lista)
    function buscarDados(){

        $res = array();
        $cmd = $this->pdo->query(
            "SELECT * FROM pessoa
            ORDER BY nome"
        );

        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    //FUNÇÃO DE CADASTRAR PESSOAS
    function cadastrarPessoa($nome, $telefone, $email){

        //ANTES DE CADASTRAR VAMOS VERIFICAR SE JÁ POSSUI O EMAIL CADASTRADO
        $cmd = $this->pdo->prepare(
            "SELECT id FROM pessoa
            WHERE email = :e"
        );
        $cmd->bindValue(":e",$email);
        $cmd->execute();

        //rowcount() conta a quantidade de linhas devolvidas
        if ($cmd->rowCount() > 0) {
            return false;
        }else{
            $cmd = $this->pdo->prepare(
                "INSERT INTO pessoa(nome,telefone,email)
                VALUES (:n,:t,:e)"
            );
            $cmd->bindValue(":n",$nome);
            $cmd->bindValue(":t",$telefone);
            $cmd->bindValue(":e",$email);
            $cmd->execute();
            return true;
        }

    }

    //FUNÇÃO DE EXCLUIR PESSOAS
    function excluirPessoa($id){
        $cmd = $this->pdo->prepare(
            "DELETE FROM pessoa
            WHERE id = :id"
        );
        $cmd->bindValue(":id",$id);
        $cmd->execute();
    }

    //BUSCAR DADOS DE UMA PESSOA
    function buscarDadosPessoa($id){
        $res = array();
        $cmd = $this->pdo->prepare(
            "SELECT * FROM pessoa
            WHERE id = :id"
        );
        $cmd->bindValue(":id",$id);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    //FUNÇÃO DE ATUALIZAR DADOS NO BANCO DE DADOS
    function atualizarDados($id, $nome, $telefone, $old_email, $new_email){

        $cmd = $this->pdo->prepare(
            "SELECT id FROM pessoa
            WHERE email = :e"
        );
        $cmd->bindValue(":e",$new_email);
        $cmd->execute();

        if ($cmd->rowCount() > 0 && $new_email != $old_email) {
            return false;
        }else{
            $cmd = $this->pdo->prepare(
                "UPDATE pessoa
                SET nome = :n, telefone = :t, email = :e
                WHERE id = :id"
            );
            $cmd->bindValue(":id",$id);
            $cmd->bindValue(":n",$nome);
            $cmd->bindValue(":t",$telefone);
            $cmd->bindValue(":e",$new_email);
            $cmd->execute();
            return true;
        }
    }

}


?>