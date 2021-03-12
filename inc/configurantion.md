Configuration.php
===

Instruções
---
-	Crie o configuration.php em inc/
-	Copie e cole contéudo do arquivo configuration.php
-	Adicione as  suas informações de servidor, usuário e senha



Exemplo: configuration.php
---
```php
<?php
class Sql {

	//construtor

	public $conn;


	public function __construct(){
		return $this->conn =  mysqli_connect('127.0.0.1','my_user','my_password','hcode_shop');
	}

	public function __destruct(){
		mysqli_close($this->conn);
	}

	public function query($string_query){
		return mysqli_query($this->conn, $string_query);
	}
}

?>
```

Obs:
---
O arquivo configuration.php está configurado em .gitignore