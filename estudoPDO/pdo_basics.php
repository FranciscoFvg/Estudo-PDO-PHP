<?php 


//-----------------------Aula 01 - Conexão com o banco de dados--------------------//
try {
    $pdo = new PDO("mysql:dbname=CRUDPDO;host=localhost", "root","");
    echo 'Conexão com banco de dados bem-sucedida!' . '<br>';
} catch (PDOException $e) {
    echo "Erro de banco de dados: " . $e->getMessage();
}
catch(\throwable $e){
    echo "Erro genérico: " . $e->getMessage();
}

//-----------------------Aula 02 - Uso do INSERT-----------------------------------//

//1° forma de uso do INSERT:
//Passa-se o método para uma variável($res) para usar outros métodos
$res = $pdo ->prepare(
    "INSERT INTO pessoa(nome,telefone,email)
    VALUES (:n,:t,:e)"
    //values podem receber variáveis com nomes escolhidos(sempre usando os : na frente da variável)
);

//2 formas de substituir as váriavéis de values:
//bindValue substitui (parametro, value) onde value pode ser o valor bruto, variável ou função
$res->bindValue(":n", "Vitor");
$res->bindValue(":t", "88993192397");
$res->bindValue(":e", "vitorteste@gmail.com");
//O método execute() aplica/executa todas as alterações na variável $res
$res->execute();

//bindParam apenas substitui (parametro, value) ond value é uma variavel
/* $nome = "Vitor";
$res->bindParam(":n", $nome); */

//2° forma de uso do INSERT:
//O query() realiza uma execução direta e recebe os valores brutos também
$pdo ->query(
    "INSERT INTO pessoa(nome,telefone,email)
    VALUES ('Miriam', '123456789', 'teste@gmail.com')"
);

//-----------------------Aula 03 - Uso do DELETE e UPDATE--------------------------//

//##############################DELETE############################################
//Todas as operações básicas de banco de dados podem usar o prepare() e o query()
//prepare()
$cmd = $pdo->prepare(
    "DELETE FROM pessoa
    WHERE id = :id"
);
$id = 2;
$cmd->bindValue(":id",$id);
$cmd->execute();

//query()
$pdo->query(
    "DELETE FROM pessoa
    WHERE id = '3'"
);

//##############################UPDATE############################################
$cmd = $pdo->prepare(
    "UPDATE pessoa
    SET email = :e
    WHERE id = :id"
);
$email = "ograndecara@gmail.com";
$id = 11;
$cmd->bindValue(":e",$email);
$cmd->bindValue(":id",$id);
$cmd->execute();

$pdo->query(
    "UPDATE pessoa
    SET email = 'ola@gmail.com'
    WHERE id = '12'"
);

//-----------------------Aula 04 - Uso do SELECT-----------------------------------//
$cmd = $pdo->prepare(
    "SELECT * FROM pessoa
    WHERE id = :id"
);
$id = 11;
$cmd->bindValue(":id",$id);
$cmd->execute();
//Ao usar o SELECT a variável $cmd vai receber o resultado do banco, mas não em array
//Deve-se usar uma função para transformar o resultado em array e passar isso a uma variável

//1°forma:
//Quando recebe-se apenas uma linha
//O banco de dados envia o valor "id" e o indice, mas isso é desnecessário
//Para corrigir passamos um parâmetro para o fetch()
$resultado = $cmd->fetch(PDO::FETCH_ASSOC);

//ou
//2° forma:
//Quando recebe-se várias linhas
//$cmd->fetchAll();

//Exibir dados.
foreach ($resultado as $key => $value) {
    echo $key.": ".$value."<br>";
}

?>