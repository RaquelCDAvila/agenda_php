<?php

session_start();

include_once("conection.php");
include_once("url.php");

$data = $_POST;

//MODIFICAÇÃO NO BANCO
if(!empty($data)){
    

    //CRIAR CONTATO
    if($data["type"] === "create"){
        $name = $data["name"];
        $phone = $data["phone"];
        $observation = $data["observation"];

        $query = "INSERT INTO contacts (name, phone, observation) VALUES (:name, :phone, :observation)";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":observation", $observation);

        try{

            $stmt->execute();
            $_SESSION["msg"] = "Contato adicionado com sucesso!";
            
            } catch(PDOException $e) {
             //erro de conexão
             $error = $e->getMessage();
             echo "Erro: $error";
            }
    }
    //REDIRECT HOME
    header("Location:" . $BASE_URL . "../index.php");
//SELEÇÃO DE DADOS
} else{
    $id;

    if(!empty($_GET)){
        $id = $_GET["id"];
    }
    
    //RETORNA DADOS DE UM CONTATO
    if(!empty($id)){
        $query = "SELECT * FROM contacts WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $contact = $stmt->fetch();
    }else{
        //RETORNA TODOS OS CONTATOS
        $contacts = [];
        $query = "SELECT * FROM contacts";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $contacts = $stmt->fetchAll();
    }
}
//FECHAR CONEXCÃO
$conn = null;


