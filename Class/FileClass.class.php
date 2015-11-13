<?php
class usuario{
    public $_user_cod;
    public $_user_name;
    public $_user_login;
    public $_user_pass;
    public $_user_age;
            function __construct($cod,$name,$login,$pass,$age){         //modulo construtor para pegar o basico do usuario
                $this->_user_cod   = $cod;
                $this->_user_name  = $name;
                $this->_user_login = $login;
                $this->_user_pass  = $pass;
                $this->_user_age   = $age;
            }
            public function login_user(){    //função de login do usuario
                $cod   = $this->_user_cod;
                $login = $this->_user_login;
                $pass  = $this->_user_pass;
                require_once 'config/connection_server.php';   //arquivo de conexao
                $sql = "select COD_U,login,senha from usuario where login = :login and senha = :senha"; //comando de pesquisa
                $dados = array(
                  ':cod_user' => $cod,  
                  ':login'    => $login,   // DADOS DO USUARIO 
                  ':login'    => $pass  
                );
                $query = $banco->prepare($sql);
                $query-execute($dados);
                if($query->rowcount()>=1){
                   while($i=$query->fetch(PDO::FETCH_OBJ)):   //confirmação de existencia e redirecionando se verdadeiro
                       if($login == $i->login && $pass == $i->senha){
                           session_start();
                           $_SESSION['cod'] = $i->cod;
                       }else{}
                   endwhile; 
                }else{
                   session_start();
                   $_SESSION['menMenssagem']++;
                }
                $sql   = "insert into usuario_login(COD_UL,cod_usu,horario_login)values(null,:cod_user,null)";  //cod_user vem do $dados na linha 23
                $query = $banco->prepare($sql);
                $query->execute($dados);  //executa o o sql para sinalizar o login do usuario
                header('Location:index2.php');  // redireciona para a pagina de usuario
            }
            public function exit_user(){
                require_once 'config/connection_server.php';
                $cod   = $this->_user_cod;   // pega o cod do usuario
                $_SESSION['cod'] = '0';
                $sql = "insert into usuario_logout(COD_UL,cod_usu,horario_logout)values(null,:cod_user,null)";   // sql para sinalizar o logout do usuario
                $dados = array(
                    ':cod_user' => $cod,
                );
                $query = $banco->prepare($sql);
                $query->execute($dados);          //executa o comando para sinalizar o logout do usuario
                header('location:index.php');
            }
}
class amigos extends usuario{
    public $_user_cod2;
    public $_user_name2;
    public $_user_login2;
    public $_user_pass2;
    public $_user_age2;
            
            function __construct($cod2,$name2,$login2,$pass2,$age2){         //modulo construtor para pegar o basico do usuario
                $this->_user_cod2   = $cod2;
                $this->_user_name2  = $name2;
                $this->_user_login2 = $login2;
                $this->_user_pass2  = $pass2;
                $this->_user_age2   = $age2;
            }
            function viewFriends(){
                $cod_user  = $this->_user_cod;
                require_once 'config/connection_server.php';
                $sql = "select * from amigos where COD_U1 = :cod_user or COD_U2 = :cod_user";
                $dados = array(
                    'cod_user'  => $cod_user,
                    'cod_user2' => $cod_user2
                );
                $query = $banco->prepare($sql);
                $query->execute($dados);    
                if($query->rowcount()>=1){
                   while($i=$query->fetch(PDO::FETCH_OBJ)):   //confirmação de existencia e redirecionando se verdadeiro
                       if($cod_user == $i->COD_U1){
                           echo $i->COD_U2;
                       }elseif($cod_user == $i->COD_U2){
                           echo $i->COD_U1;
                       }
                   endwhile; 
                }else{}
            }
            public function addFriends(){
                $cod_user  = $this->_user_cod;
                $cod_user2 = $this->_user_cod2;
                require_once 'config/connection_server.php';
                $sql = "insert into amigos(COD_U1,COD_U2)values(:cod_user,:cod_user2)";
                $dados = array(
                    'cod_user'  => $cod_user,
                    'cod_user2' => $cod_user2
                );
                $query = $banco->prepare($sql);
                $query->execute($dados);
                header("location:index2.php");
            }
            public function removeFriends(){
                $cod_user  = $this->_user_cod;
                $cod_user2 = $this->_user_cod2;
                require_once 'config/connection_server.php';
                $sql="delete from amigos where :cod_user = COD_U1 and :cod_user2 = COD_U2 or :cod_user = COD_U2 and :cod_user2 = COD_U1";
                $dados= array(
                    'cod_user'  => $cod_user,
                    'cod_user2' => $cod_user2
                );
                $query=$banco->prepare($sql);
                $query->execute($dados);
            }
}

            